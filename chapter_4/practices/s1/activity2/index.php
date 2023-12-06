<?php
require_once 'templates/header.php';
?>

<?php
$array = [
    [
        'username' => 'Rady',
        'message' => 'Hello !'
    ],
    [
        'username' => 'Hyacinthe',
        'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'
    ]
];
?>

<?php
$host     = 'localhost'; // Because MySQL is running on the same computer as the web server
$database = 'php_connect'; // Name of the database you use (you need first to CREATE DATABASE in MySQL)
$user     = 'root'; // Default username to connect to MySQL is root
$password = 'mysql'; // Default password to connect to MySQL is empty

// TO DO: CREATE CONNECTION TO DATABASE
// Read file: https://www.w3schools.com/php/php_mysql_connect.asp
try {
    $db = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
  } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  } 
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['username']) && !empty($_POST['message'])) :
    $username = $_POST['username'];
    $message = $_POST['message'];

    $statement = $db->prepare("INSERT INTO posts (username, message) VALUES (:username, :message)");
    $statement->execute([
        ':username' => $username,
        ':message' => $message
    ]);

    $newPost = [
        'username' => $username,
        'message' => $message
    ];
    array_push($array, $newPost);
endif;
?>

<?php
// TO DO: SELECT ALL POSTS FROM DATABASE
$statement = $db->query("SELECT username, message FROM posts");
$posts = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($array as $post) :
?>
    <div class="card">
        <div class="card-header">
            <span><?php echo $post['username']                     // TO DO: display the value of username for this $array ?></span>
        </div>
        <div class="card-body">
            <p class="card-text"><?php echo $post['message'];       // TO DO: display the message for this post ?></p>
        </div>
    </div>
    <hr>
<?php
endforeach;
?>

<form action="#" method="post">
    <div class="row mb-3 mt-3">
        <div class="col">
            <input type="text" class="form-control" placeholder="Enter Name" name="username">
        </div>
    </div>

    <div class="mb-3">
        <textarea name="message" placeholder="Enter message" class="form-control"></textarea>
    </div>
    <div class="d-grid">
        <button type="submit" class="btn btn-primary">Add new post</button>
    </div>
</form>

<?php
require_once 'templates/footer.php';
?>