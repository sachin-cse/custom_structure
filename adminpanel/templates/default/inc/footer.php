            <!-- footer -->
            <footer class="footer"> &copy; <?php echo date('Y');?> by <a href="<?php echo SITE_LOC_PATH;?>/" target="_blank" class="company"><?php echo SITE_NAME;?></a>. All rights reserved. <?php echo DEVELOPED_BY;?></footer>
            <!-- End footer -->
        </div>
        <!-- End Page wrapper  -->
    </div>
    <!-- End Wrapper -->
   
    <!-- Bootstrap tether Core JavaScript -->
    <script src="<?php echo ADMIN_TMPL_PATH;?>/js/lib/popper.min.js"></script>
    <script src="<?php echo ADMIN_TMPL_PATH;?>/js/lib/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="<?php echo ADMIN_TMPL_PATH;?>/js/lib/jquery.slimscroll.js"></script>
    <!--Menu sidebar -->
    <script src="<?php echo ADMIN_TMPL_PATH;?>/js/lib/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="<?php echo ADMIN_TMPL_PATH;?>/js/lib/sticky-kit.min.js"></script>
    <script src="<?php echo ADMIN_TMPL_PATH;?>/js/lib/theia-sticky-sidebar.min.js"></script>
    <!--Custom JavaScript -->
    <script src="<?php echo ADMIN_TMPL_PATH;?>/js/lib/toastr.min.js"></script>
    <script src="<?php echo ADMIN_TMPL_PATH;?>/js/lib/sweetalert.min.js"></script>
    <!-- DateTimePicker -->
    
    <script src="<?php echo ADMIN_TMPL_PATH;?>/js/lib/json2html.min.js"></script>
    <script src="<?php echo ADMIN_TMPL_PATH;?>/js/lib/jquery.json2html.min.js"></script>

    <script src="<?php echo ADMIN_TMPL_PATH;?>/js/scripts.js"></script>
    <script src="<?php echo ADMIN_TMPL_PATH;?>/js/custom.js"></script>

    <script type="importmap">
        {
            "imports": {
                "ckeditor5": "<?php echo ADMIN_TMPL_PATH;?>/plugins/ckeditor5/ckeditor5.js",
                "ckeditor5/": "<?php echo ADMIN_TMPL_PATH;?>/plugins/ckeditor5/"
            }
        }
    </script>
    <script type="module" src="<?php echo ADMIN_TMPL_PATH;?>/js/ckeditor_config.js"></script>
</body>

</html>