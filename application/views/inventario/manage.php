<?php if (!$this->input->is_ajax_request()): ?>
    <?php $this->load->view("partial/header"); ?>
<?php endif; ?>

<div class="container">
    <h2>Gestión de Inventario</h2>

    <!-- Dropdown de tienda solo para administradores; para empleados, tienda fija oculta -->
    <?php if (isset($is_admin) && $is_admin): ?>
    <div class="form-group form-group-sm" style="margin-bottom:15px;">
        <label for="stock_location" class="control-label" style="color:black;font-weight:bold;">Seleccionar Tienda:</label>
        <div style="width:20%;display:inline-block;vertical-align:middle;margin-left:10px;">
            <?php echo form_dropdown(
                'stock_location',
                $stock_locations,
                $stock_location,
                ['id'=>'stock_location','class'=>'form-control selectpicker','data-live-search'=>'true']
            ); ?>
        </div>
    </div>
    <?php else: ?>
    <!-- Usuario no admin: tienda fija oculta -->
    <input type="hidden" id="stock_location" value="<?= $stock_location ?>">
    <?php endif; ?>

    <!-- Filtro de categoría -->
    <div class="form-group form-group-sm" style="margin-bottom:15px;">
        <label for="category_filter" class="control-label" style="color:black;font-weight:bold;">Filtrar Categoría:</label>
        <div style="width:20%;display:inline-block;vertical-align:middle;margin-left:10px;">
            <?php echo form_dropdown('category_filter', $categories, '', 'id="category_filter" class="form-control"'); ?>
        </div>
    </div>

    <!-- Controles de inventario -->
    <div class="row" style="margin-bottom:15px;">
        <div class="col-xs-12">
            <button id="start_inventario" class="btn btn-primary">Iniciar Inventario</button>
            <input type="text" id="barcode" class="form-control" placeholder="Escanea o ingresa el código de barras" style="display:inline-block;width:300px;margin:0 10px;">
            <button id="scan_item" class="btn btn-success">Agregar</button>
            <button id="delete_selected" class="btn btn-danger">Borrar Seleccionados</button>
        </div>
    </div>

    <!-- Tabla de resultados -->
    <table id="inventario_table" class="table table-bordered">
        <thead>
            <tr>
                <th>Seleccionar</th>
                <th>UPC/EAN/ISBN</th>
                <th>Nombre Artículo</th>
                <th>Categoría</th>
                <th>Precio de Venta</th>
                <th>Cantidad en Stock</th>
                <th>Cantidad Contada</th>
            </tr>
        </thead>
        <tbody id="inventario_list"></tbody>
    </table>

    <button id="finish_inventario" class="btn btn-finalizar" style="background-color:#003366;color:white;margin-top:10px;">Finalizar Inventario</button>
</div>

<script type="text/javascript">
$(document).ready(function() {
    var inventoryStarted = false;
    var inventoryData    = {};

    // Obtener tienda actual (dropdown o hidden)
    function getCurrentLocation() {
        return $('#stock_location').val();
    }

    // Iniciar inventario
    $('#start_inventario').click(function() {
        $.post('<?= site_url("inventario/start_inventory") ?>', function(response) {
            alert(response.success||response.message||'Iniciado Inventario, Operación completada.');
            $('#inventario_list').empty(); inventoryData = {}; inventoryStarted = true;
        }, 'json');
    });

    // Enter en código => agregar
    $('#barcode').keypress(function(e){ if(e.which===13){ $('#scan_item').click(); return false; }});
    
    
      // Actualizar inventoryData cuando se edite manualmente cantidad contada
    $(document).on('change', '.counted_quantity_input', function() {
        var key = $(this).closest('tr').attr('id').replace('row_', '');
        var val = parseInt($(this).val(), 10) || 0;
        if (inventoryData[key]) {
            inventoryData[key].counted_quantity = val;
        }
    });

    // Agregar artículo
    $('#scan_item').click(function() {
        if (!inventoryStarted) { alert('Debe iniciar el inventario antes.'); return; }
        var code = $('#barcode').val().trim(); if(!code) return;
        var loc  = getCurrentLocation(); var cat = $('#category_filter').val();
        $.post('<?= site_url("inventario/scan_item") ?>', { item_number: code, stock_location: loc }, function(resp) {
            if(!resp.success){ alert(resp.message); $('#barcode').val(''); return; }
            var item = resp.item, key = item.item_number||code;
            if(cat && item.category!==cat){ alert('No pertenece a la categoría.'); $('#barcode').val(''); return; }
            if(inventoryData[key]){
                inventoryData[key].counted_quantity++;
                $('#row_'+key+' .counted_quantity_input').val(inventoryData[key].counted_quantity);
            } else {
                inventoryData[key] = { item_id:item.item_id, item_number:key, name:item.name, category:item.category, unit_price:item.unit_price, expected_quantity:item.expected_quantity||0, counted_quantity:1 };
                var row = '<tr id="row_'+key+'">'+
                          '<td><input type="checkbox" class="delete_checkbox" value="'+key+'"></td>'+ 
                          '<td>'+key+'</td>'+       
                          '<td>'+item.name+'</td>'+ 
                          '<td>'+item.category+'</td>'+ 
                          '<td>'+item.unit_price+'</td>'+ 
                          '<td>'+ (item.expected_quantity||0) +'</td>'+ 
                          '<td><input type="number" min="0" class="counted_quantity_input form-control" style="width:80px;" value="1"></td>'+ 
                          '</tr>';
                $('#inventario_list').append(row);
            }
            $('#barcode').val('');
        }, 'json');
    });

    // Borrar seleccionados
    $('#delete_selected').click(function() {
        $('.delete_checkbox:checked').each(function(){ var k=$(this).val(); delete inventoryData[k]; $('#row_'+k).remove(); });
    });

    // Finalizar inventario
    $('#finish_inventario').click(function(){ if(!inventoryStarted){ return alert('No hay inventario iniciado.'); } var loc=getCurrentLocation();
        $.post('<?= site_url("inventario/finish_inventory") ?>', { inventory:inventoryData, stock_location:loc }, function(resp){ if(!resp.success){ return alert(resp.message);} 
            var diffs=[]; $.each(inventoryData,function(_,it){ var d=it.counted_quantity-it.expected_quantity; if(d!==0){ diffs.push(it.name+' ('+it.item_number+'): '+(d>0?'Sobrante de '+d:'Faltante de '+Math.abs(d))); }});
            if(diffs.length){ alert('Diferencias:\n\n'+diffs.join('\n')); } else { alert('¡Todo cuadra!'); } 
            $('#inventario_list').empty(); inventoryData={}; inventoryStarted=false;
        }, 'json');
    });

    // Cambiar tienda reinicia tabla
    $('#stock_location').change(function(){ $('#inventario_list').empty(); inventoryData={}; });
});
</script>

<?php if (!$this->input->is_ajax_request()): ?>
    <?php $this->load->view("partial/footer"); ?>
<?php endif; ?>
