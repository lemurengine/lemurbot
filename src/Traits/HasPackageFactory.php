<?php
namespace LemurEngine\LemurBot\Traits;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

trait HasPackageFactory
{
    use HasFactory;

    protected static function newFactory()
    {
        $modelName = Str::after(get_called_class(), 'Models\\');
        $path = "Database\\Factories\\{$modelName}Factory";

        return call_user_func($path . '::new');
    }
}
