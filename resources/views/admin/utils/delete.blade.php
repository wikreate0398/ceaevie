<div id="deleteModal_{{ $table }}_{{ $id }}" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title">Подтвердите действие</h4>
         </div>
         <div class="modal-body">
            <p>
               Вы действительно хотите удалить?
            </p>
         </div>
         <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn default">Отмена</button>
            <button type="button" 
                    data-dismiss="modal" 
                    onclick="Ajax.toDelete(this, '{{ $table }}', '{{ $id }}')" 
                    class="btn red">
               Удалить
            </button>
         </div>
      </div>
   </div>
</div>