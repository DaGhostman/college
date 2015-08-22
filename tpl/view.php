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

/**
 * @var $link Database
 */
if (!array_key_exists('id', $_GET)) {
    $students = $link->fetch('students');
    $courses = $link->fetch('courses');
    $html = '';

    if ($students === false) {
        echo 'Error occurred while fetching list of students';
        exit;
    }

    foreach ($students as $student) {
        $html .= <<<HTML
<tr>
    <td>{$student['id']}.</td>
    <td>{$student['firstName']}</td>
    <td>{$student['lastName']}</td>
    <td>
        <a href="/?action=delete&id={$student['id']}">DELETE</a> |
        <a href="/?action=view&id={$student['id']}">VIEW</a> |
        <a href="/?action=update&id={$student['id']}"> UPDATE </a>
    </td>
</tr>
HTML;
        $html .= PHP_EOL;
    }

    echo <<<TABLE
<table cellspacing="0" cellpadding="0">
    <thead>
        <td>ID</td>
        <td>Name</td>
        <td>Surname</td>
        <td>Action</td>
    </thead>
    <tbody>
    {$html}
    </tbody>
</table>
TABLE;
} else {
    $r = $link->custom(sprintf(
        'SELECT students.firstName, students.lastName, students.id, courses.title FROM students JOIN courses ON (courses.id = students.course) WHERE students.id = %d',
        $_GET['id']
    ));

    $student = $r->fetch_assoc();

    if ($student === null) {
        echo '<meta http-equiv="refresh" content="3;url=/?action=view" />';
        echo '<p style="color: green">Record for student with id: ' . $_GET['id'] . ' was not found</p>' . PHP_EOL;
        exit;
    }

    $names = $student['firstName'] . ' ' . $student['lastName'];
    $course = $student['title'];

    echo <<<TABLE
<table cellspacing="0" cellpadding="0">
    <tr>
        <td>ID</td><td>{$student['id']}</td>
    </tr>
    <tr>
        <td>Names</td><td>{$student['firstName']} {$student['lastName']}</td>
    </tr>
    <tr>
        <td>Course</td><td>{$student['title']}</td>
    </tr>
    <tr>
        <td> <a href="/?action=delete&id={$student['id']}" onclick="return confirm('Are you sure you want to delete this record');"> Delete </a> </td>
        <td> <a href="/?action=update&id={$student['id']}"> Update </a> </td>
    </tr>
</table>
TABLE;
}