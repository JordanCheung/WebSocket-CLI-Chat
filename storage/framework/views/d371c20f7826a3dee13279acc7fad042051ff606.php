<?php $__env->startSection('content'); ?>
    <div class="col-md  ">
        <img class="img-thumbnail" src="<?php echo e($picture); ?>"/>
        <h2>Thanks for signing in <?php echo e($name); ?>.</h2>
        <h2>Here is your cli login code</h2>
        <code >
            <?php echo e($code); ?>

        </code>


        <h2> or </h2>
        <a href="/chat" class="btn btn-light btn-lg">
            click here to start web chat!
        </a>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('welcome', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/jordan/Developer/assignment3/websocket_server/resources/views/dashboard.blade.php ENDPATH**/ ?>