
<table class="table">
    <tr ng-repeat="letter in letters.data" ng-show="!letter.deleted_at">
        <td>
            <a href letter-edit="letter.id" codes="letterInfo.codes" class="btn btn-default">@{{ letter.id }}</a><br>
        </td>
        <td>@{{ letter.date }}</td>
        <td>
            <div ng-repeat="info in letter.information | filterCode:fields">@{{ info.code }} @{{ info.data }}</div>
        </td>
        <td>
            <div class="row">
                <div class="col-md-6">
                    <div ng-repeat="sender in letter.senders">
                        <a href person-preview="sender.id">@{{ sender.name_2013 }}</a>
                    </div>
                    <div ng-show="letter.senders.length == 0">
                        <em>unbekannt</em>
                    </div>
                </div>
                <div class="col-md-6">
                    <div ng-repeat="receiver in letter.receivers">
                        <a href person-preview="receiver.id">@{{ receiver.name_2013 }}</a>
                    </div>
                    <div ng-show="letter.receivers.length == 0">
                        <em>unbekannt</em>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">&nbsp;</div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div ng-show="letter.from != null">
                        <a href location-preview="letter.from.id">@{{ letter.from.name }}</a>
                    </div>
                    <div ng-show="letter.from == null">
                        <em>unbekannt</em>
                    </div>
                </div>
                <div class="col-md-6">
                    <div ng-show="letter.to != null">
                        <a href location-preview="letter.to.id">@{{ letter.to.name }}</a>
                    </div>
                    <div ng-show="letter.to == null">
                        <em>unbekannt</em>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</table>