<?php


namespace chibicc\Struct;


class File
{
    public string $name;
    public int $file_no;
    public string $contents;

    // For #line directive
    public string $display_name;
    public int $line_delta;

    /**
     * File constructor.
     * @param string $name
     * @param int $file_no
     * @param string $contents
     * @param string $display_name
     * @param int $line_delta
     */
    public function __construct(string $name, int $file_no, string $contents, string $display_name, int $line_delta)
    {
        $this->name = $name;
        $this->file_no = $file_no;
        $this->contents = $contents;
        $this->display_name = $display_name;
        $this->line_delta = $line_delta;
    }

}