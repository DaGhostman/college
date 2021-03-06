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
 * Verifies that the user has logged in before trying to open the page
 * otherwise redirect them to the login page
 */
if (!array_key_exists('gap-login', $_COOKIE)) {
    header('Location: /PHP/college/', true);
    exit;
}

/**
 * @var $link Database
 */

if (array_key_exists('fname', $_POST) && array_key_exists('sname', $_POST)) {
/*
 * This doc block gets triggered when the form had been submitted.
 * The check consists of the 2 keys 'fname' and 'sname' being presented
 * as POST data
 */
    $link->insert('students', [
        0,
        $_POST['fname'],
        $_POST['sname'],
        (int) $_POST['course']
    ]);

    echo '<meta http-equiv="refresh" content="3;url=/PHP/college/?action=view" />';
    echo '<p style="color: green">Record added successfully</p>' . PHP_EOL;
} else {
    /*
     * If the above condition is not met, assume that the intended behaviour is
     * display the form which is used to add rows to the table.
     */
    $courses = $link->fetch('courses');
    $c = '';
    /**
     * Loop through all the values in $courses and build the select box's options
     */
    foreach ($courses as $course) {
        $c .= '<option value="'. $course['id'] . '">' . $course['title'] . '</option>' . PHP_EOL;
    }

    /*
     * The HEREDOC to be displayed with the html form
     */
    echo <<<HTML
<form action="/PHP/college/?action=insert" method="post">
    <label>First Name: <input type="text" name="fname" /></label><br />
    <label>Last Name: <input type="text" name="sname" /></label><br />
    <label for="course">Course</label>
    <select name="course" id="course">
        <option disabled="disabled" selected="selected">Please select a course</option>
        <option disabled="disabled">-------------------------</option>
        {$c}
    </select>
    <button type="submit">Save</button>
    <button type="reset">Clear</button>
</form>
HTML;
}
