
<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Utils\Utils;

class UtilsTest extends TestCase
{
    // Tests pour sortStudents (TDD)

    public function test_should_sort_students_by_grade_ascending(): void
    {
        $students = [
            ['name' => 'Bob', 'grade' => 14, 'age' => 20],
            ['name' => 'Alice', 'grade' => 10, 'age' => 22]
        ];

        $sorted = Utils::sortStudents($students, 'grade', 'asc');

        $this->assertEquals(10, $sorted[0]['grade']);
        $this->assertEquals(14, $sorted[1]['grade']);
    }

    public function test_should_sort_students_by_grade_descending(): void
    {
        $students = [
            ['name' => 'Alice', 'grade' => 10, 'age' => 22],
            ['name' => 'Bob', 'grade' => 14, 'age' => 20]
        ];

        $sorted = Utils::sortStudents($students, 'grade', 'desc');

        $this->assertEquals(14, $sorted[0]['grade']);
        $this->assertEquals(10, $sorted[1]['grade']);
    }

    public function test_should_sort_students_by_name_ascending(): void
    {
        $students = [
            ['name' => 'Charlie', 'grade' => 12, 'age' => 21],
            ['name' => 'Alice', 'grade' => 15, 'age' => 20]
        ];

        $sorted = Utils::sortStudents($students, 'name', 'asc');

        $this->assertEquals('Alice', $sorted[0]['name']);
        $this->assertEquals('Charlie', $sorted[1]['name']);
    }

    public function test_should_sort_students_by_age_ascending(): void
    {
        $students = [
            ['name' => 'Bob', 'grade' => 12, 'age' => 25],
            ['name' => 'Alice', 'grade' => 15, 'age' => 19]
        ];

        $sorted = Utils::sortStudents($students, 'age', 'asc');

        $this->assertEquals(19, $sorted[0]['age']);
        $this->assertEquals(25, $sorted[1]['age']);
    }

    public function test_should_return_empty_array_for_null_or_empty_input(): void
    {
        $this->assertEquals([], Utils::sortStudents(null));
        $this->assertEquals([], Utils::sortStudents([]));
    }

    public function test_should_not_modify_original_array(): void
    {
        $students = [
            ['name' => 'Bob', 'grade' => 14, 'age' => 20],
            ['name' => 'Alice', 'grade' => 10, 'age' => 22]
        ];

        $original = $students;

        Utils::sortStudents($students, 'grade', 'asc');

        $this->assertEquals($original, $students);
    }
}
