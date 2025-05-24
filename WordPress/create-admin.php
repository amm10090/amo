<?php
/**
 * WordPress 临时管理员账户创建脚本
 * 警告：仅在紧急情况下使用，执行后会自动删除
 * 
 * 使用方法：
 * 1. 将此文件上传到WordPress根目录
 * 2. 在浏览器中访问：http://yourdomain.com/create-admin.php
 * 3. 脚本执行后会自动删除自身
 */


// 检查是否为WordPress环境
if (!file_exists('./wp-config.php')) {
    die('错误：未找到 wp-config.php 文件。请确保将脚本放在WordPress根目录。');
}

// 加载WordPress配置
require_once('./wp-config.php');

// 管理员账户配置
$admin_config = [
    'username' => 'emergency_admin',
    'password' => 'TempAdmin@' . date('Ymd') . '!' . rand(100, 999),
    'email' => 'admin@' . $_SERVER['HTTP_HOST'],
    'display_name' => '临时管理员',
    'role' => 'administrator'
];

/**
 * 创建管理员用户
 */
function createEmergencyAdmin($config) {
    global $wpdb;
    
    try {
        // 连接数据库
        $connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
        if ($connection->connect_error) {
            throw new Exception('数据库连接失败: ' . $connection->connect_error);
        }
        
        // 检查用户是否已存在
        $check_user = $connection->prepare("SELECT ID FROM {$wpdb->prefix}users WHERE user_login = ? OR user_email = ?");
        $check_user->bind_param('ss', $config['username'], $config['email']);
        $check_user->execute();
        $result = $check_user->get_result();
        
        if ($result->num_rows > 0) {
            throw new Exception('用户名或邮箱已存在');
        }
        
        // 生成密码哈希
        $password_hash = wp_hash_password($config['password']);
        
        // 准备用户数据
        $user_registered = current_time('mysql');
        $user_nicename = sanitize_title($config['username']);
        $display_name = $config['display_name'];
        
        // 插入用户记录
        $insert_user = $connection->prepare("
            INSERT INTO {$wpdb->prefix}users 
            (user_login, user_pass, user_nicename, user_email, user_registered, user_status, display_name) 
            VALUES (?, ?, ?, ?, ?, 0, ?)
        ");
        
        $insert_user->bind_param('ssssss', 
            $config['username'], 
            $password_hash, 
            $user_nicename, 
            $config['email'], 
            $user_registered, 
            $display_name
        );
        
        if (!$insert_user->execute()) {
            throw new Exception('用户创建失败: ' . $insert_user->error);
        }
        
        $user_id = $connection->insert_id;
        
        // 设置用户能力（管理员权限）
        $capabilities = serialize(['administrator' => true]);
        $user_level = 10;
        
        // 插入用户元数据
        $meta_data = [
            ['nickname', $config['username']],
            ['first_name', '临时'],
            ['last_name', '管理员'],
            ['description', '紧急创建的临时管理员账户'],
            ["{$wpdb->prefix}capabilities", $capabilities],
            ["{$wpdb->prefix}user_level", $user_level],
            ['show_admin_bar_front', 'true'],
            ['admin_color', 'fresh']
        ];
        
        $insert_meta = $connection->prepare("
            INSERT INTO {$wpdb->prefix}usermeta (user_id, meta_key, meta_value) 
            VALUES (?, ?, ?)
        ");
        
        foreach ($meta_data as $meta) {
            $insert_meta->bind_param('iss', $user_id, $meta[0], $meta[1]);
            $insert_meta->execute();
        }
        
        $connection->close();
        
        return [
            'success' => true,
            'user_id' => $user_id,
            'username' => $config['username'],
            'password' => $config['password'],
            'email' => $config['email']
        ];
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}

/**
 * WordPress密码哈希函数（简化版）
 */
function wp_hash_password($password) {
    // 使用WordPress标准的密码哈希方法
    $hasher = new PasswordHash(8, true);
    return $hasher->HashPassword(trim($password));
}

/**
 * 简化的PasswordHash类
 */
class PasswordHash {
    var $itoa64;
    var $iteration_count_log2;
    var $portable_hashes;
    var $random_state;

    function __construct($iteration_count_log2, $portable_hashes) {
        $this->itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $this->iteration_count_log2 = $iteration_count_log2;
        $this->portable_hashes = $portable_hashes;
        $this->random_state = microtime() . uniqid(rand(), TRUE);
    }

    function get_random_bytes($count) {
        $output = '';
        if (is_readable('/dev/urandom') && ($fh = @fopen('/dev/urandom', 'rb'))) {
            $output = fread($fh, $count);
            fclose($fh);
        }
        if (strlen($output) < $count) {
            $output = '';
            for ($i = 0; $i < $count; $i += 16) {
                $this->random_state = md5(microtime() . $this->random_state);
                $output .= pack('H*', md5($this->random_state));
            }
            $output = substr($output, 0, $count);
        }
        return $output;
    }

    function encode64($input, $count) {
        $output = '';
        $i = 0;
        do {
            $value = ord($input[$i++]);
            $output .= $this->itoa64[$value & 0x3f];
            if ($i < $count)
                $value |= ord($input[$i]) << 8;
            $output .= $this->itoa64[($value >> 6) & 0x3f];
            if ($i++ >= $count)
                break;
            if ($i < $count)
                $value |= ord($input[$i]) << 16;
            $output .= $this->itoa64[($value >> 12) & 0x3f];
            if ($i++ >= $count)
                break;
            $output .= $this->itoa64[($value >> 18) & 0x3f];
        } while ($i < $count);
        return $output;
    }

    function gensalt_private($input) {
        $output = '$P$';
        $output .= $this->itoa64[min($this->iteration_count_log2 + 5, 30)];
        $output .= $this->encode64($input, 6);
        return $output;
    }

    function crypt_private($password, $setting) {
        $output = '*0';
        if (substr($setting, 0, 2) == $output)
            $output = '*1';
        $id = substr($setting, 0, 3);
        if ($id != '$P$' && $id != '$H$')
            return $output;
        $count_log2 = strpos($this->itoa64, $setting[3]);
        if ($count_log2 < 7 || $count_log2 > 30)
            return $output;
        $count = 1 << $count_log2;
        $salt = substr($setting, 4, 8);
        if (strlen($salt) != 8)
            return $output;
        $hash = md5($salt . $password, TRUE);
        do {
            $hash = md5($hash . $password, TRUE);
        } while (--$count);
        $output = substr($setting, 0, 12);
        $output .= $this->encode64($hash, 16);
        return $output;
    }

    function gensalt_extended($input) {
        $count_log2 = min($this->iteration_count_log2 + 8, 24);
        $count = (1 << $count_log2) - 1;
        $output = '_';
        $output .= $this->itoa64[$count & 0x3f];
        $output .= $this->itoa64[($count >> 6) & 0x3f];
        $output .= $this->itoa64[($count >> 12) & 0x3f];
        $output .= $this->itoa64[($count >> 18) & 0x3f];
        $output .= $this->encode64($input, 3);
        return $output;
    }

    function gensalt_blowfish($input) {
        $itoa64 = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $output = '$2a$';
        $output .= chr(ord('0') + $this->iteration_count_log2 / 10);
        $output .= chr(ord('0') + $this->iteration_count_log2 % 10);
        $output .= '$';
        $i = 0;
        do {
            $c1 = ord($input[$i++]);
            $output .= $itoa64[$c1 >> 2];
            $c1 = ($c1 & 0x03) << 4;
            if ($i >= 16) {
                $output .= $itoa64[$c1];
                break;
            }
            $c2 = ord($input[$i++]);
            $c1 |= $c2 >> 4;
            $output .= $itoa64[$c1];
            $c1 = ($c2 & 0x0f) << 2;
            $c2 = ord($input[$i++]);
            $c1 |= $c2 >> 6;
            $output .= $itoa64[$c1];
            $output .= $itoa64[$c2 & 0x3f];
        } while (1);
        return $output;
    }

    function HashPassword($password) {
        $random = '';
        if (CRYPT_BLOWFISH == 1 && !$this->portable_hashes) {
            $random = $this->get_random_bytes(16);
            $hash = crypt($password, $this->gensalt_blowfish($random));
            if (strlen($hash) == 60)
                return $hash;
        }
        if (CRYPT_EXT_DES == 1 && !$this->portable_hashes) {
            if (strlen($random) < 3)
                $random = $this->get_random_bytes(3);
            $hash = crypt($password, $this->gensalt_extended($random));
            if (strlen($hash) == 20)
                return $hash;
        }
        if (strlen($random) < 6)
            $random = $this->get_random_bytes(6);
        $hash = $this->crypt_private($password, $this->gensalt_private($random));
        if (strlen($hash) == 34)
            return $hash;
        return '*';
    }
}

/**
 * 清理函数：删除脚本文件
 */
function cleanup() {
    $script_file = __FILE__;
    if (file_exists($script_file)) {
        unlink($script_file);
        return true;
    }
    return false;
}

// 执行脚本
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WordPress 临时管理员创建工具</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f1f1f1;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #c3e6cb;
            margin: 20px 0;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #f5c6cb;
            margin: 20px 0;
        }
        .warning {
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #ffeaa7;
            margin: 20px 0;
        }
        .credential-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 4px;
            border-left: 4px solid #007cba;
            margin: 20px 0;
            font-family: monospace;
        }
        .credential-box strong {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }
        .btn {
            background: #007cba;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 10px 0;
        }
        .btn:hover {
            background: #005a87;
        }
        code {
            background: #f1f1f1;
            padding: 2px 4px;
            border-radius: 3px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚨 WordPress 临时管理员创建工具</h1>
        
        <div class="warning">
            <strong>⚠️ 安全警告：</strong>
            此工具仅用于紧急情况。执行完成后脚本将自动删除。
        </div>

        <?php
        if (isset($_GET['action']) && $_GET['action'] === 'create') {
            $result = createEmergencyAdmin($admin_config);
            
            if ($result['success']) {
                echo '<div class="success">';
                echo '<h3>✅ 管理员账户创建成功！</h3>';
                echo '<div class="credential-box">';
                echo '<strong>登录凭据（请立即保存）：</strong>';
                echo '<p><strong>用户名：</strong> ' . htmlspecialchars($result['username']) . '</p>';
                echo '<p><strong>密码：</strong> ' . htmlspecialchars($result['password']) . '</p>';
                echo '<p><strong>邮箱：</strong> ' . htmlspecialchars($result['email']) . '</p>';
                echo '<p><strong>用户ID：</strong> ' . $result['user_id'] . '</p>';
                echo '</div>';
                echo '<p><a href="/wp-admin/" class="btn">🔗 前往后台登录</a></p>';
                echo '</div>';
                
                // 自动清理
                if (cleanup()) {
                    echo '<div class="success">';
                    echo '<p>✅ 脚本文件已自动删除，安全清理完成。</p>';
                    echo '</div>';
                } else {
                    echo '<div class="warning">';
                    echo '<p>⚠️ 请手动删除此脚本文件以确保安全：<code>' . basename(__FILE__) . '</code></p>';
                    echo '</div>';
                }
                
            } else {
                echo '<div class="error">';
                echo '<h3>❌ 创建失败</h3>';
                echo '<p>错误信息：' . htmlspecialchars($result['error']) . '</p>';
                echo '</div>';
            }
        } else {
            ?>
            <h3>📋 使用说明</h3>
            <ol>
                <li>确认你有权限在此WordPress网站上创建管理员账户</li>
                <li>点击下方按钮创建临时管理员账户</li>
                <li>使用生成的凭据登录WordPress后台</li>
                <li>登录后立即更改密码并创建正式管理员账户</li>
                <li>删除或禁用临时账户</li>
            </ol>
            
            <h3>⚙️ 账户配置</h3>
            <div class="credential-box">
                <p><strong>用户名：</strong> <?php echo htmlspecialchars($admin_config['username']); ?></p>
                <p><strong>邮箱：</strong> <?php echo htmlspecialchars($admin_config['email']); ?></p>
                <p><strong>显示名：</strong> <?php echo htmlspecialchars($admin_config['display_name']); ?></p>
                <p><strong>权限：</strong> 管理员</p>
                <p><em>密码将在创建时自动生成</em></p>
            </div>
            
            <a href="?action=create" class="btn" onclick="return confirm('确定要创建临时管理员账户吗？')">
                🚀 创建管理员账户
            </a>
            
            <div class="warning">
                <h4>🔒 安全提醒：</h4>
                <ul>
                    <li>此脚本执行后将自动删除</li>
                    <li>请在创建账户后立即登录并更改密码</li>
                    <li>建议创建正式管理员账户后删除临时账户</li>
                    <li>不要在生产环境长期保留此脚本</li>
                </ul>
            </div>
            <?php
        }
        ?>
        
        <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">
        <p style="text-align: center; color: #666; font-size: 14px;">
            WordPress 临时管理员创建工具 | 仅用于紧急情况
        </p>
    </div>
</body>
</html>