<?php 
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model{
    protected $table = 'users';
    protected $primaryKey = 'Id';

    protected $allowedFields = ['Username', 'Password', 'Email', 'CreatedAt', 'UpdatedAt'];
}
?>