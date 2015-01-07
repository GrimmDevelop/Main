<div class="modal-header">
    <h3 class="modal-title">Letter @{{ letter.id }}</h3>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-3">
            <h4>Senders</h4>
            <div ng-repeat="sender in letter.senders">
                <a href person-preview="sender.id">@{{ sender.name_2013 }}</a>
            </div>
        </div>
        <div class="col-sm-6" ng-if="letterIsValid">
            <h4>Map</h4>
            From <strong>@{{ letter.from.name }}</strong> to <strong>@{{ letter.to.name }}</strong>
            <div ng-if="showMap">
                <ui-gmap-google-map center="center" zoom="zoom" control="mapInstance"></ui-gmap-google-map>
            </div>
        </div>
        <div class="col-md-6" ng-if="!letterIsValid">
            The letter is invalid! One of both locations isn't corrent assigned!
        </div>
        <div class="col-sm-3">
            <h4>Receivers</h4>
            <div ng-repeat="receiver in letter.receivers">
                <a href person-preview="receiver.id">@{{ receiver.name_2013 }}</a>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-default" ng-click="zoomFit()"><span class="glyphicon glyphicon-map-marker"></span></button>
    <button class="btn btn-default" ng-click="cancel()">Close</button>
</div>
