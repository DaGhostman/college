<?php
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

/**
 * If the user is logged in, there is no need to ask them to login again (retarded?)
 */
if (array_key_exists('gap-login', $_COOKIE)) {
    header('Location: /PHP/college/?action=view', true);
    exit;
}

if (!empty($_POST)) {
    $r = $link->custom(sprintf(
        'SELECT id FROM credentials WHERE username = \'%s\' AND secret = MD5(\'%s\')',
        $_POST['username'],
        $_POST['password']
    ));

    if ($r->fetch_assoc()['id'] !== null) {
        setcookie('gap-login', '1', strtotime('1 hour'));
        echo '<meta http-equiv="refresh" content="0;url=/PHP/college/?action=view" />';
    } else {
        echo '<span style="color: red">Invalid login details</span>';
    }
}
    echo <<<HTML
    <h2 style="color: orange">You need to login to access the resources</h2>
<form method="post" target="_self">
<label for="usr">Username: <input type="text" name="username" id="usr"/></label><br />
<label for="pwd">Password: <input type="password" name="password" id="pwd"/></label><br />
<button type="submit">Login</button>
</form>
HTML;
