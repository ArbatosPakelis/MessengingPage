<?php 
namespace App\Models;
use CodeIgniter\Model;

class EmailQueueModel extends Model{
    protected $table = 'emailqueue';
    protected $primaryKey = 'Id';

    protected $allowedFields = ['Password', 'Email'];
}
?>