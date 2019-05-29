<nav class="topBar">
    <div class="container">

        <ul class="topBarNav pull-right">

            <li class="dropdown">
                <a href="{{url('/showCart')}}"> <i class="fa fa-shopping-basket mr-5"></i> <span class="hidden-xs">
                                Cart<sup class="text-primary">@if($cart) {{$cart->itemsCount}} @else @endif</sup>
                                <i class="fa fa-angle-down ml-5"></i>
                            </span> </a>
                <ul class="dropdown-menu cart w-250" role="menu">
                    <li>
                        <div class="cart-items">
                            <ol class="items">
                                <li>
                                    <a href="#" class="product-image"> <img src="https://lh3.googleusercontent.com/-uwagl9sPHag/WM7WQa00ynI/AAAAAAAADtA/hio87ZnTpakcchDXNrKc_wlkHEcpH6vMwCJoC/w140-h148-p-rw/profile-pic.jpg" class="img-responsive" alt="Sample Product "> </a>
                                    <div class="product-details">
                                        <div class="close-icon"> <a href="#"><i class="fa fa-close"></i></a> </div>
                                        <p class="product-name"> <a href="#">Sumi9xm@gmail.com</a> </p> <strong>1</strong> x <span class="price text-primary">$59.99</span> </div>
                                    <!-- end product-details -->
                                </li>
                                <!-- end item -->
                                <li>
                                    <a href="#" class="product-image"> <img src="https://lh3.googleusercontent.com/-Gy3KAlilHAw/WNf7a2eL5YI/AAAAAAAAD2Y/V3jUt14HiZA3HLpeOKkSaOu57efGuMw9ACL0B/w245-d-h318-n-rw/shoes_01.jpg" class="img-responsive" alt="Sample Product "> </a>
                                    <div class="product-details">
                                        <div class="close-icon"> <a href="#"><i class="fa fa-close"></i></a> </div>
                                        <p class="product-name"> <a href="#">Lorem Ipsum dolor sit</a> </p> <strong>1</strong> x <span class="price text-primary">$39.99</span> </div>
                                    <!-- end product-details -->
                                </li>
                                <!-- end item -->
                                <li>
                                    <a href="#" class="product-image"> <img src="https://lh3.googleusercontent.com/-ydDc-0L0WFY/WNf7a6Awe_I/AAAAAAAAD2Y/I8IzJtYRWegkOUxCZ5SCK6vbdiiSxVsCQCL0B/w245-d-h318-n-rw/bags_07.jpg" class="img-responsive" alt="Sample Product "> </a>
                                    <div class="product-details">
                                        <div class="close-icon"> <a href="#"><i class="fa fa-close"></i></a> </div>
                                        <p class="product-name"> <a href="#">Lorem Ipsum dolor sit</a> </p> <strong>1</strong> x <span class="price text-primary">$29.99</span> </div>
                                    <!-- end product-details -->
                                </li>
                                <!-- end item -->
                            </ol>
                        </div>
                    </li>
                    <li>
                        <div class="cart-footer"> <a href="#" class="pull-left"><i class="fa fa-cart-plus mr-5"></i>View
                                Cart</a> <a href="#" class="pull-right"><i class="fa fa-shopping-basket mr-5"></i>Checkout</a> </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav><!--=========-TOP_BAR============-->