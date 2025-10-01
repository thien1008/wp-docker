<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<!-- Tải Font Awesome qua link (thay vì @import để tránh lỗi tải) -->
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

<style>
	.wp-block-pages-list__item__link{
		color: white !important;
	}
	.cat-item >a{
		color: white !important;
	}
	#main{
		padding: 0 !important;
	}
    /* Footer */
    #footer {
        background: #007b5e !important;
        color: #ffffff;
        padding: 20px 0;
    }
    #footer h5 {
        padding-left: 13px; /* Điều chỉnh để border-left thẳng hàng với icon >> */
        border-left: 3px solid #eeeeee;
        padding-bottom: 6px;
        margin-bottom: 20px;
        color: #ffffff;
        font-size: 18px;
    }
    #footer .footer-widget {
        margin-bottom: 15px;
    }
    #footer .footer-widget ul {
        list-style: none;
        padding: 0;
    }
    #footer .footer-widget ul li {
        padding: 5px 0;
        transition: all 0.3s ease; /* Thêm transition cho hiệu ứng mượt */
    }
    #footer .footer-widget ul li a {
        color: white;
        text-decoration: none;
        transition: all 0.3s ease; /* Thêm transition cho hiệu ứng mượt */
    }
    #footer .footer-widget ul li:hover {
        transform: translateX(10px); /* Di chuyển sang phải khi hover */
    }
    #footer .footer-widget ul li a:hover {
        color: #ffffff; /* Màu trắng đậm hơn khi hover */
        font-weight: bold; /* Tăng độ đậm */
    }
    #footer .social {
        text-align: center;
        margin: 20px 0;
    }
    #footer .social li {
        display: inline-block;
        margin: 0 10px;
    }
    #footer .social li a {
        display: inline-block; /* Thêm để transform hoạt động */
    }
    #footer .social li a i {
        font-size: 25px;
        color: white !important;
        transition: all 0.3s ease; /* Giảm thời gian transition */
    }
    #footer .social li a:hover i {
        transform: scale(1.2); /* Phóng to 20% thay vì thay đổi font-size */
        color: #eeeeee !important;
    }
    #footer .footer-bottom {
        text-align: center;
        padding-top: 10px;
        border-top: 1px solid #ffffff;
    }
    @media (max-width: 767px) {
        #footer h5 {
            padding-left: 0;
            border-left: transparent;
            margin-bottom: 10px;
        }
        #footer .col-md-4 {
            margin-bottom: 20px;
        }
    }

    /* Tùy chỉnh danh sách trang (Pages List) */
    .wp-block-page-list {
        list-style-type: none;
        padding: 0;
        color: #ffffff;
        background-color: #007b5e;
    }
    .wp-block-page-list li {
        padding: 5px 0;
        margin-left: 10px;
        transition: all 0.8s ease; /* Thêm transition */
    }
    .wp-block-page-list li a {
        color: #ffffff;
        text-decoration: none;
        position: relative;
        padding-left: 20px;
        transition: all 0.3s ease; /* Thêm transition */
    }
    .wp-block-page-list li a::before {
        content: "\f101"; /* Unicode fa-angle-double-right */
        font-family: 'FontAwesome';
        position: absolute;
        left: 0;
        color: #ffffff;
        font-size: 16px;
    }
    /* Fallback nếu Font Awesome không tải: Hiển thị >> */
    .wp-block-page-list li a:before:not([class*="fa"]) {
        content: ">> " !important;
        font-family: inherit !important;
    }
    /* Đánh dấu trang hiện tại */
    .wp-block-page-list li.current-menu-item a {
        font-weight: bold;
        color: #ffffff;
    }
    .wp-block-page-list li:hover {
        transform: translateX(10px); /* Di chuyển sang phải khi hover */
    }
    .wp-block-page-list li a:hover {
        color: #ffffff; /* Màu trắng đậm */
        font-weight: bold; /* Tăng độ đậm */
    }
    .wp-block-page-list li:hover {
        color: #ffffff; /* Màu trắng đậm */
        font-weight: bold; /* Tăng độ đậm */
    }
    .wp-block-page-list li a:hover::before {
        color: #ffffff;
        font-weight: bold;
    }

    /* Responsive cho Pages List */
    @media (max-width: 767px) {
        .wp-block-page-list li {
            margin-left: 5px;
        }
        .wp-block-page-list li a {
            padding-left: 15px;
        }
        .wp-block-page-list li a::before {
            font-size: 14px;
        }
    }

    /* Tùy chỉnh danh sách danh mục (Categories List) */
    .wp-block-categories-list {
        list-style-type: none;
        padding: 0;
    }
    .wp-block-categories li {
        padding: 5px 0;
        margin-left: 10px;
        transition: all 0.3s ease; /* Thêm transition */
    }
    .wp-block-categories li a {
        text-decoration: none;
        position: relative;
        padding-left: 20px;
        transition: all 0.3s ease; /* Thêm transition */
    }
    .wp-block-categories li a::before {
        content: "\f101"; /* Unicode fa-angle-double-right */
        font-family: 'FontAwesome';
        position: absolute;
        left: 0;
        color: inherit;
        font-size: 16px;
    }
    /* Fallback nếu Font Awesome không tải: Hiển thị >> */
    .wp-block-categories li a:before:not([class*="fa"]) {
        content: ">> " !important;
        font-family: inherit !important;
    }
    .wp-block-categories li:hover {
        transform: translateX(10px); /* Di chuyển sang phải khi hover */
    }
    .wp-block-categories li a:hover {
        color: #ffffff; /* Màu trắng đậm */
        font-weight: bold; /* Tăng độ đậm */
    }

    /* Responsive cho Categories List */
    @media (max-width: 767px) {
        .wp-block-categories li {
            margin-left: 5px;
        }
        .wp-block-categories li a {
            padding-left: 15px;
        }
        .wp-block-categories li a::before {
            font-size: 14px;
        }
    }

    .ml-ql{
        margin-left: 10px;
    }

    /* Đã xóa .wp-block-pages-list__item:hover cũ để tránh xung đột */
</style>

<!-- Footer -->
<section id="footer">
    <div class="container">
        <div class="row" style="margin-top: 20px;">
            <div class="col-md-4">
                <h5 class="ml-ql">Quick links</h5>
                <?php if (is_active_sidebar('sidebar-1')) : ?>
                    <?php dynamic_sidebar('sidebar-1'); ?>
                <?php endif; ?>
            </div>
            <div class="col-md-4">
                <h5 class="ml-ql">Quick links</h5>
                <?php if (is_active_sidebar('sidebar-2')) : ?>
                    <?php dynamic_sidebar('sidebar-2'); ?>
                <?php endif; ?>
            </div>
            <div class="col-md-4">
                <h5 class="ml-ql">Quick links</h5>
                <?php if (is_active_sidebar('sidebar-3')) : ?>
                    <?php dynamic_sidebar('sidebar-3'); ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-12 social">
                <ul class="list-unstyled list-inline">
                    <li class="list-inline-item"><a href="https://www.facebook.com"><i class="fa fa-facebook"></i></a></li>
                    <li class="list-inline-item"><a href="https://www.twitter.com"><i class="fa fa-twitter"></i></a></li>
                    <li class="list-inline-item"><a href="https://www.instagram.com"><i class="fa fa-instagram"></i></a></li>
                    <li class="list-inline-item"><a href="https://plus.google.com"><i class="fa fa-google-plus"></i></a></li>
                    <li class="list-inline-item"><a href="mailto:info@example.com"><i class="fa fa-envelope"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="row footer-bottom">
            <div class="col-12">
                <p>National Transaction Corporation is a Registered MSP/ISO of Elavon, Inc. Georgia [a wholly owned subsidiary of U.S. Bancorp, Minneapolis, MN]</p>
                <p>© All right Reversed. <a style='color:white!important' href="https://www.sunlimetech.com" target="_blank">Sunlimetech</a></p>
            </div>
        </div>
    </div>
</section>
<!-- ./Footer -->