<?php

namespace App\Http\Controllers\Admin2;

use App\Http\Controllers\Controller;

use AdminSection;
use Illuminate\Http\Request;

class Admin2Controller extends Controller
{

  public function __construct() {
    $this->middleware('role:root|admin|moderator');
  }


  public function index() {
    $content = 'Панель';

  	return AdminSection::view($content, 'Панель');
  }

}
