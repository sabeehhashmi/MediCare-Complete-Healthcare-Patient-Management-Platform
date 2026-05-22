<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 text-center">
                <script>document.write(new Date().getFullYear())</script> © Mednero.
            </div>
            <!-- <div class="col-sm-6">
                <div class="text-sm-end d-none d-sm-block">
                    Crafted with <i class="mdi mdi-heart text-danger"></i> by <a href="https://themesdesign.com/" target="_blank" class="text-reset">Themesdesign</a>
                </div>
            </div> -->
        </div>
    </div>
</footer>


<!-- Modal -->
<div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Enter Location</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="">
            <div class="row">
                <div class="col-12 mb-3">
                  <input type="text" class="form-control autocomplete" required placeholder="673C+W8V - Dubai - United Arab Emirates" id="txt_location" name="location" value=""/>
                  <input type="hidden" id="current-latitude" name="latitude" value=""/>
                  <input type="hidden" id="current-longitude" name="longitude" value=""/>
                  <input type="hidden" id="current-location" name="location">
                </div>
                <div class="col-12 mb-3">
                  <div id="map_canvas" style="height: 200px;width:100%;"></div>
                </div>
                <div class="col-12">
                  <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary w-100" id="set-location-button">Set Location</button>
                    
                    <button type="button" class="btn btn-primary w-100"  id="set-current-loc">Set Current Location</button>
                  </div>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
