<div class="modal-header">
    <h3 class="modal-title">Distance map (@{{ computedLetters }} letters computed)</h3>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-12">
            <div ng-if="showMap">
                <ui-gmap-google-map center="center" zoom="zoom" control="mapInstance" class="extended"></ui-gmap-google-map>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-default" ng-click="zoomFit()"><span class="glyphicon glyphicon-map-marker"></span></button>
    <button class="btn btn-default" ng-click="cancel()">Close</button>
</div>
