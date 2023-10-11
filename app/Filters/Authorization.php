<?php
namespace App\Filters;
class Authorization implements \CodeIgniter\Filters\FilterInterface
{

    public function before(\CodeIgniter\HTTP\RequestInterface $request, $arguments = null)
    {

    }

    public function after(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, $arguments = null)
    {
        // TODO: Implement after() method.
    }
}