<?php
namespace App\Filters;
use App\Models\UserModel;
use Config\Services;

class MyAuthorization implements \CodeIgniter\Filters\FilterInterface
{

    public function before(\CodeIgniter\HTTP\RequestInterface $request, $arguments = null)
    {
        if (!session()->get("logged_in")) {
            return redirect()->to(base_url().'user/login');
        }
    }

    public function after(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, $arguments = null)
    {
        // TODO: Implement after() method.
    }
}