
<div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="sizesModalLabel">Sizes and Qantity</h4>
      </div>
      <div class="modal-body container-fluid">
        <?php for($i=1; $i <= 12; $i++ ): ?>
          <div class="form-group col-md-4">
            <label for="size<?=$i; ?>">Size:</label>
            <input type="text" name="size<?=$i;?>" id="size<?=$i;?>" value="<?=((!empty($sreArray[$i-1]))?$sreArray[$i-1] : ''); ?>" class="form-control">
          </div>
          <div class="form-group col-md-2">
            <label for="qty<?=$i; ?>">Quantity:</label>
            <input type="number" name="qty<?=$i;?>" id="qty<?=$i;?>" value="<?=((!empty($qreArray[$i-1]))?$qreArray[$i-1] : ''); ?>" min="0" class="form-control">
          </div>
        <?php endfor; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updateSizes();jQuery('#sizesModal').modal('toggle'); return false;">Save changes</button>
      </div>
    </div>
  </div>
</div>
