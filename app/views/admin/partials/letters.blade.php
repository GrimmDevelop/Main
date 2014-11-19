
<div class="row">
    <div class="col-md-12">
        <alert ng-if="message" type="@{{ message.type }}" close="closeMessage()">@{{ message.message }}</alert>
    </div>
</div>
<div class="row">
    <div class="col-sm-2">
        <input class="form-control" ng-model="openLetterId" placeholder="type in a letter id">
    </div>
    <div class="col-sm-10">
        <button class="btn btn-default" ng-click="openLetterWithId()"><span class="glyphicon glyphicon-edit"></span></button>
    </div>
</div>
<div class="row">
    <div class="col-md-2" style="margin: 20px 0;">
        <select class="form-control" ng-model="itemsPerPage" ng-change="reload()" ng-options="option for option in itemsPerPageOptions"></select>
    </div>
    <div class="col-md-10">
        <pagination total-items="letters.total" ng-model="currentPage" ng-change="reload()" items-per-page="letters.per_page"
            max-size="7" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" boundary-links="true"></pagination>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <form role="form">
            <div class="checkbox">
                <label><input type="checkbox" ng-model="showLettersWithErrors.from" ng-change="reload()" /> show only letters with from errors</label>
            </div>
            <div class="checkbox">
                <label><input type="checkbox" ng-model="showLettersWithErrors.to" ng-change="reload()" /> show only letters with to errors</label>
            </div>
            <div class="checkbox">
                <label><input type="checkbox" ng-model="showLettersWithErrors.senders" ng-change="reload()" /> show only letters with sender errors</label>
            </div>
            <div class="checkbox">
                <label><input type="checkbox" ng-model="showLettersWithErrors.receivers" ng-change="reload()" /> show only letters with receiver errors</label>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table">
            <tr ng-repeat="letter in letters.data">
                <td><a href letter-edit="letter.id" class="btn btn-default">@{{ letter.id }}</a></td>
                <td>@{{ letter.code }}</td>
                <td>
                    <div ng-repeat="info in letter.information | filterCode:['absendeort','absort_ers','absender','empf_ort','empfaenger','dr']">@{{ info.code }} @{{ info.data }}</div>
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
    </div>
</div>

<div class="row">
    <div class="col-md-2" style="margin: 20px 0;">
        <select class="form-control" ng-model="itemsPerPage" ng-change="reload()" ng-options="option for option in itemsPerPageOptions"></select>
    </div>
    <div class="col-md-10">
        <pagination total-items="letters.total" ng-model="currentPage" ng-change="reload()" items-per-page="letters.per_page"
            max-size="7" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" boundary-links="true"></pagination>
    </div>
</div>
