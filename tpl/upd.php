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

if (!array_key_exists('gap-login', $_COOKIE)) {
    header('Location: /', true);
    exit;
}

if (array_key_exists('id', $_GET) && !empty($_POST)) {
    if ($link->update('students', (int) $_GET['id'], $_POST)) {
        echo '<meta http-equiv="refresh" content="3;url=/?action=view&id=' . (int) $_GET['id'] . '" />';
        echo '<p style="color: green">Record updated successfully</p>' . PHP_EOL;
    } else {
        echo 'Unable to update record, please try again';
    }

} else {
    $link->sanitize($_GET['id']);
    $resource = $link->custom(sprintf(
        'SELECT * FROM students WHERE id = \'%d\' LIMIT 1',
        (int) $_GET['id']
    ));

    $student = $resource->fetch_assoc();

    $courses = '';
    foreach ($link->fetch('courses') as $course) {
        $selected = null;
        if ($course['id'] === $student['course']) {
            $selected = 'selected="selected"';
        }

        $courses .= '<option value="'.$course['id'].'" ' . $selected . '>' . $course['title'] . '</option>';
    }

    echo <<<HTML
<form action="/?action=update&id={$_GET['id']}" method="post" onsubmit="return confirm('Are you sure you want to save the changes?');">
<label for="id">ID: </label><br /><input id="id" name="id" type="number" value="{$student['id']}" /><br />
<label for="name">First Name: </label><br /><input id="name" name="firstName" type="text" value="{$student['firstName']}" /><br />
<label for="surname">Last Name: </label><br /><input id="surname" name="lastName" type="text" value="{$student['lastName']}" /><br />
<label for="course">Course: </label><br />
<select name="course" id="course">
    $courses
</select>
<br />
<button type="submit">Save</button> &nbsp;&nbsp; <button type="button" onclick="resetInitial()">Undo Changes</button>
<br />
</form>
<script>
 <!-- Function that preserves the initial information of the form, so that it could reset the originals -->
    function resetInitial() {
        var data = {
            id: parseInt({$_GET['id']}),
            course: parseInt({$student['course']}),
            firstName: "{$student['firstName']}",
            lastName: "{$student['lastName']}"
        };

        for (var i in data) {
            if (data.hasOwnProperty(i)) {
                document.getElementsByName(i)[0].value = data[i];
            }
        }
    }
</script>
HTML;

}
