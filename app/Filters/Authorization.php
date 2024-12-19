<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Authorization implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        //GET CURRENT REQUEST URL
        $path = $request->getUri()->getPath();

        //GET CLEAN PATH
        $path = str_replace("/index.php", "", $path);

        //VERIFY IF AN ONLINE USER IS TRYING TO ACCESS THE FOLLOWING ROUTES (/, signin, signup), IF YES, REDIRECT TO HOME
        if (($path === "/" || $path === "/signin" || $path === "/signup") && session()->has("online_user")) {
            return redirect()->to("/home");
        }

        //VERIFY IF IT HAS AN ONLINE USER TO ACCESS PROTECTED RESOURCES
        if (($path !== "/" && $path !== "/signin" && $path !== "/signup") && !session()->has("online_user")) {
            return redirect()->to("/");
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
