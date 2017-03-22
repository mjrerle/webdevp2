<?php include 'templates/header.php'; ?>

<!-- Carousel
================================================== -->
<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner" role="listbox">
        <div class="item active">
            <img class="first-slide" src="img/background-1932466_1920.jpg" alt="First slide">
            <div class="container">
                <div class="carousel-caption">
                    <h1 style="font-size: 6em;">CT310 Ingredients Emporium</h1>
                    <h1>Fresh Food Your Way.</h1>
                    <p></p>
                    <p><a class="btn btn-lg btn-primary" href="products.php" role="button">Shop Now</a></p>
                </div>
            </div>
        </div>
        <div class="item">
            <img class="second-slide" src="img/watermelon-815072_1920.jpg" alt="Second slide">
            <div class="container">
                <div class="carousel-caption">
                    <h1 style="font-size: 6em;">CT310 Ingredients Emporium</h1>
                    <h1>Food delivery has never been this simple</h1>
                    <p>Local. Organic. Fresh.</p>
                    <p><a class="btn btn-lg btn-primary" href="about.php" role="button">Learn more about us</a></p>
                </div>
            </div>
        </div>
        <div class="item">
            <img class="third-slide" src="img/apple-661726_1920.jpg" alt="Third slide">
            <div class="container">
                <div class="carousel-caption">
                    <h1 style="font-size: 6em;">CT310 Ingredients Emporium</h1>
                    <h1>Our mission is simple</h1>
                    <p>We want satisfied customers the old fashioned way: by quality food and quality service.</p>
                    <p><a class="btn btn-lg btn-primary" href="contact.php" role="button">Tell us how we're doing</a></p>
                </div>
            </div>
        </div>
    </div>
    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div><!-- /.carousel -->

<div class="marketing container">
    <div class="jumpbar">

    </div>
    <div class="row" id="marketingOverview">
        <div class="col-md-4 featurette">
            <div class="featurette-image">
                <img class="img-rounded" src="img/barley-872000_640.jpg" alt="image">
            </div>
            <h3>Fresh and Organic</h3>
            <p class="featurette-text">
                We source all of our ingredients from organic farms that are local to you. This means that you are always getting the freshest ingredients possible and supporting your local farmers.
            </p>
            <a class="btn btn-lg btn-primary" href="#freshness" role="button">Learn More</a>
        </div>
        <div class="col-md-4 featurette">
            <div class="featurette-image">
                <img class="img-rounded" src="img/semi-truck-1285131_640.jpg" alt="image">
            </div>
            <h3>Fast Shipping</h3>
            <p class="featurette-text">
                All of our ingredients are shipped from farms and producers to one of our same-state storage facilities. There your orders are fulfilled, and our ingredients are shipped to you as quickly as possible.
            </p>
            <a class="btn btn-lg btn-primary" href="#shipping" role="button">Learn More</a>
        </div>
        <div class="col-md-4 featurette">
            <div class="featurette-image">
                <img class="img-rounded" src="img/tomato-1205699_1280.jpg" alt="image">
            </div>
            <h3>Five-Star Quality</h3>
            <p class="featurette-text">
                Before our products are shipped to you, they must first go through our rigorous quality assurance testing. Our product standards are very high, and we will not send you anything that does not meet those standards. Period.
            </p>
            <a class="btn btn-lg btn-primary" href="#quality" role="button">Learn More</a>
        </div>
    </div>
    <hr>
    <div class="row feature-big" id="freshness">
        <h1 style="text-align: center; margin-bottom: 40px;">Always fresh. Always organic.</h1>
        <div class="col-md-5">
            <img class="img-rounded" src="img/grapes-690230_1280.jpg" style="width: 100%" alt="organic"/>
        </div>
        <div class="col-md-7">
            <p>
                Our business model is simple: we find farms and producers near you, and we ship you their products. That's all there is to it. No hassle, no worry, no stress. You can always count on the ingredients that you receive from us, to be as fresh, if not more so, than what you would find at your local market.
            </p>
            <p>
                And you don't have to worry about whether or not you are buying organic. We will only sell you a product if it has been certified organic. So you can be sure that you are always buying the best organic products, for less than what you would pay at a grocery store.
            </p>
        </div>
    </div>
    <hr>
    <div class="row feature-big" id="shipping">
        <h1 style="text-align: center; margin-bottom: 40px;">Ships next day, arrives same week.</h1>
        <div class="col-md-7">
            <p>
                In keeping of our main goal, to deliver the freshest ingredient possible to your doorstep, we have a product storage facility in just about every state in the U.S. The has the added benefit that you receive your ingredients quickly, typically in 2 to 3 days.
            </p>
            <p>
                We order all of our products straight from local farmers and producers, and then we ship them to you. Keeping this process simple ensures timeliness, freshness, and your satisfaction.
            </p>
        </div>
        <div class="col-md-5">
            <img class="img-rounded" src="img/truck-1042600_1280.jpg" style="width: 100%" alt="delivery"/>
        </div>
    </div>
    <hr>
    <div class="row feature-big" id="quality">
        <h1 style="text-align: center; margin-bottom: 40px;">Nothing less than the finest.</h1>
        <div class="col-md-5">
            <img class="img-rounded" src="img/restaurant-bern-179046_1280.jpg" style="width: 100%" alt="delivery"/>
        </div>
        <div class="col-md-7">
            <p>
                As stated before, we only sell you 100% certified organic products. And even then, we won't sell you products from just any organic producer. They must first meet our very rigorous quality guidelines. We won't sell you anything less, no matter how close it came.
            </p>
            <p>
                We test all of the products receive for any quality defects when they arrive. If there is anything we find unsatisfactory, it will never reach your door.
            </p>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
