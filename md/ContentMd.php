<?php
return [
    'table' => 'cms_contents',
    'cols' => [
        // Identifier
        'id' => [
            'type' => 'Sequence',
            'is_null' => false,
            'editable' => false
        ],
        // Fields
        'name' => [
            'type' => 'Varchar',
            'is_null' => false,
            'size' => 64,
            'unique' => true,
            'editable' => true
        ],
        'title' => [
            'type' => 'Varchar',
            'is_null' => true,
            'size' => 250,
            'default' => '',
            'editable' => true
        ],
        'description' => [
            'type' => 'Varchar',
            'is_null' => true,
            'size' => 2048,
            'default' => 'auto created content',
            'editable' => true
        ],
        'mime_type' => [
            'type' => 'Varchar',
            'is_null' => true,
            'size' => 64,
            'default' => 'application/octet-stream',
            'editable' => true
        ],
        'media_type' => [
            'type' => 'Varchar',
            'is_null' => true,
            'size' => 64,
            'default' => 'application/octet-stream',
            'verbose' => 'Media type',
            'help_text' => 'This types allow you to category contents',
            'editable' => true
        ],
        'file_path' => [
            'type' => 'File',
            'is_null' => false,
            'default' => '',
            'size' => 250,
            'verbose' => 'File path',
            'help_text' => 'Content file path',
            'editable' => false,
            'readable' => false
        ],
        'file_name' => [
            'type' => 'Varchar',
            'is_null' => false,
            'size' => 250,
            'default' => 'unknown',
            'verbose' => 'file name',
            'help_text' => 'Content file name',
            'editable' => true
        ],
        'file_size' => [
            'type' => 'Integer',
            'is_null' => false,
            'default' => 'no title',
            'verbose' => 'file size',
            'help_text' => 'content file size',
            'editable' => false
        ]
    ]
];