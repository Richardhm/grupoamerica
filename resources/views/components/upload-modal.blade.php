<div id="uploadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" name="formulario_upload" id="formulario_upload" enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex">
                        <div style="flex-basis:90%;margin-right:2%;">
                            <label for="arquivo">Arquivo:</label>
                            <input type="file" name="arquivo_upload" id="arquivo_upload" class="form-control form-control-sm">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
