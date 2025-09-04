<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    // Set custom table name
    protected $table = 'appsettings';

    // Disable timestamps if your table doesn't have created_at and updated_at
    public $timestamps = false;

    public static function getCustomerService()
    {
        return self::where('Category', 'CustomerService')
                ->where('RowStatus', '>=', 0)
                ->first();
    }

    // Define fillable fields (optional but good practice)
    protected $fillable = [
        'Category',
        'Value',
        'ValueStart',
        'ValueEnd',
        'isRunning',
        'RowStatus',
        'CreatedDate',
    ];
}