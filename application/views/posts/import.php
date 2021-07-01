<div class="container">
    <div class="col-xs-12">
    <?php 
        if(!empty($success_msg)){
            echo '<div class="alert alert-success">'.$success_msg.'</div>';
        }elseif(!empty($error_msg)){
            echo '<div class="alert alert-danger">'.$error_msg.'</div>';
        }
    ?>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading"><?php echo $action ?? ''; ?> Post <a href="<?php echo site_url('posts/'); ?>" class="glyphicon glyphicon-arrow-left pull-right"></a></div>
                <div class="panel-body">
                    <form method="post" action="<?php echo site_url('posts/save'); ?>" class="form" id="importPosts" enctype="multipart/form-data">
                        <div class="form-group ">
                            <a href="<?php echo site_url('assets/samples/csv-sample-posts.csv');?>">Download Sample File</a>
                        </div>
                        <div class="form-group">
                            <input type="file" name="fileURL" id="file-url" class="filestyle" data-allowed-file-extensions="[CSV, csv]" accept=".CSV, .csv" data-buttontext="Choose File">
                            <?php echo form_error('title','<p class="help-block text-danger">','</p>'); ?>
                        </div>
                        <input type="submit" name="import_csv" id="import_csv" class="btn btn-primary" value="Submit"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>