<div class="form-group row">
    <div class="col-md-6 offset-md-4">
        <a class="btn btn-lg btn-primary btn-block text-left facebook" href="{{ url('auth/facebook') }}">
            <i class="fab fa-facebook-f"></i> <strong>Login With Facebook</strong>
        </a>
    </div>
    <div class="col-md-6 offset-md-4">
        <a class="btn btn-lg google btn-block text-left" href="{{ url('auth/google') }}">
            <i class="fab fa-google"></i> <strong>Login With Google</strong>
        </a>
    </div>
</div>

<script type="application/javascript">

    $(document).on('click', '.facebook, .google, .login, .register', function () {
        if(!$(this).find('i.fa-spinner').length){
            if($(this).hasClass('register') || $(this).hasClass('login')){
                $(this).parents('form').submit();
            }
            $(this).append(' <i class="fa fa-spinner fa-spin">');
            $('.facebook').attr('disabled', true);
            $('.google').attr('disabled', true);
            $('.register').attr('disabled', true);
        }
    });

</script>


