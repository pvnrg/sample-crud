
<div class="container">
    <?php if(!empty($success_msg)){ ?>
    <div class="col-xs-12">
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
    </div>
    <?php }elseif(!empty($error_msg)){ ?>
    <div class="col-xs-12">
        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
    </div>
    <?php } ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default ">
                <div class="panel-heading">
                    Posts 
                    
                    <a href="<?php echo site_url('posts/export/'); ?>" name="import_csv" class="btn btn-primary btn-xs pull-right mr-4"><i class="fa fa-download"></i>Export</a>
                    <a href="<?php echo site_url('posts/import/'); ?>" name="import_csv" id="import_csv" class="btn btn-success btn-xs pull-right mr-4"> <i class="fa fa-upload" aria-hidden="true"></i> Import</a>
                    <a href="<?php echo site_url('posts/add/'); ?>" name="import_csv" class="btn btn-default btn-xs pull-right mr-4"> <i class="fa fa-plus" aria-hidden="true"></i> Add New</a>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="30%">Title</th>
                            <th width="50%">Content</th>
                            <th width="15%">Action</th>
                        </tr>
                    </thead>
                    <tbody id="userData">
                        <?php if(!empty($posts)): foreach($posts as $post): ?>
                        <tr>
                            <td><?php echo '#'.$post['id']; ?></td>
                            <td><?php echo $post['title']; ?></td>
                            <td><?php echo (strlen($post['content'])>200)?substr($post['content'],0,200).'...':$post['content']; ?></td>
                            <td>
                                <a href="<?php echo site_url('posts/view/'.$post['id']); ?>" class="glyphicon glyphicon-eye-open"></a>
                                <a href="<?php echo site_url('posts/edit/'.$post['id']); ?>" class="glyphicon glyphicon-edit"></a>
                                <a href="<?php echo site_url('posts/delete/'.$post['id']); ?>" class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure to delete?')"></a>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="4">Post(s) not found......</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
