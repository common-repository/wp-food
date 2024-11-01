<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

?>
<script type="text/javascript">
  function exprintcontent(layer){
    var gennerator = window.open(",'name,");
    var layertext= document.getElementById(layer);
    gennerator.document.write(layertext.innerHTML.replace('Print Me'));
    gennerator.document.close();
    gennerator.print();
    gennerator.close();

  }
</script>
<button type="button" class="exfd-print-button" onclick="javascript:exprintcontent('printJS-form-food')">
  <?php echo esc_html__('Print Order','wp-food'); ?>
</button>
<style>
  #printJS-form-food{visibility:hidden;height: 0}
</style>
<div  id="printJS-form-food">
  <h1>This feature only for Pro version</h1>
</div>