<?php
namespace Pluf\Imgx;


use Pluf\Orm\Attribute\Entity;
use Pluf\Orm\Attribute\Table;
use Pluf\Orm\Attribute\Id;
use Pluf\Orm\Attribute\Column;


#[Entity]
#[Table("cms_contents")]
class Content
{
    #[Id]
    #[Column("id")]
    public ?int $id;
    
    #[Column("name")]
    public ?string $name;
    
    #[Column("title")]
    public ?string $title;
    
    #[Column("mime_type")]
    public ?string $mime_type;
    
    #[Column("media_type")]
    public ?string $media_type;
    
    #[Column("file_path")]
    public ?string $file_path;
    
    #[Column("file_name")]
    public ?string $file_name;
    
    #[Column("file_size")]
    public ?string $file_size;
}

