<?php
ob_start();
/**
 * @author Dimitar
 * @copyright 2015 Dimitar Dimitrov <daghostman.dimitrov@gmail.com>
 * @package college
 * @license MIT
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

require_once 'src/Database.php';
$username = 'dev';
$password = 'root';
$dbname = 'ghostApp';

$link = new Database($username, $password, $dbname);

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Dimitar Dimitrov's Project</title>
        <style>
            header {
                margin: 0 auto;
                width: 70%;
                background-color: cornflowerblue;
            }

            footer {
                margin: 0 auto;
                width: 70%;
                margin-top: 125px;
                background-color: tan;
                padding: 5px 10px;
            }

            table {
                border: 1px solid #ccc;
                margin: 0 auto;
            }

            table > thead {
                text-align: center;
            }

            table > thead > tr > td {
                font-weight: bold;
            }

            td {
                border-bottom: 1px solid #000;
                padding: 0 35px;
            }

            tr:last-child > td {
                border: 0;
            }

            tr:nth-child(odd) {
                background-color: #eee;
            }

            tr:nth-child(even) {
                background-color: aliceblue;
            }

            tr:hover {
                background-color: rgba(237, 211, 17, 0.65);
            }

            .container {
                margin: 0 auto;
                width: 85%;
                align-content: center;
                text-align: center;
                background-color: white;
            }

            a {
                text-decoration: none;
                color: white;
            }

            td > a {
                color: dodgerblue;
            }
        </style>
    </head>
    <body>
    <header>
        <h1> Unit 35 Assignment: Task 2</h1>
        <p>This is a fully functional implementation of CRUD application as required by the assignment.</p>
        <p> <a href="/">Home</a> | <a href="/?action=logout">Logout</a> </p>
    </header>
        <main class="container">
<?php
if (array_key_exists('action', $_GET)) {
    switch ($_GET['action']) {
        default:
            require_once 'tpl/end.php';
            break;
        case 'view':
            require_once 'tpl/view.php';
            break;
        case 'insert':
            require_once 'tpl/add.php';
            break;
        case 'delete':
            require_once 'tpl/del.php';
            break;
        case 'update':
            require_once 'tpl/upd.php';
            break;
        case 'logout':
            require_once 'tpl/out.php';
    }
} else {
    require_once 'tpl/login.php';
}
?>
        </main>
        <footer>
            <p style="text-align: right">
                Copyright &copy; 2015 <a href="http://opensource.org/licenses/MIT">MIT Licensed</a> <br />
                Developed by Dimitar Dimitrov &lt;daghostman.dimitrov@gmail.com&gt; <br />
                <a href="https://github.com/DaGhostman">GitHub</a> |
                <a href="http://stackoverflow.com/users/1747193/daghostman-dimitrov">StackOverflow</a> |
                <a href="https://github.com/DaGhostman/college">Sources</a>
            </p>
        </footer>
    </body>
</html>

<?php
ob_end_flush();