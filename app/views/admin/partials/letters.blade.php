
<div class="row" ng-show="mode == 'index'">
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
                <td><a href ng-click="show(letter.id)">@{{ letter.id }}</a></td>
                <td>@{{ letter.code }}</td>
                <td>
                    <div ng-repeat="information in letter.informations | filterCode:['absendeort','absort_ers','absender','empf_ort','empfaenger','dr']">@{{ information.code }} @{{ information.data }}</div>
                </td>
                <td>
                    <div class="row">
                        <div class="col-md-6">
                            <div>
                                <a href ng-click="openPersonModal(letter, 'senders')" class="btn btn-@{{ letter.senders.length == (letter.informations | countCode:'senders') ? 'default' : 'primary' }}" title="assign senders">
                                    <span class="glyphicon glyphicon-user"></span>
                                    <span class="glyphicon glyphicon-arrow-right"></span>
                                    <span class="glyphicon glyphicon-envelope"></span>
                                </a>
                            </div>
                            <div ng-repeat="sender in letter.senders">@{{ sender.name_2013 }}</div>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <a href ng-click="openPersonModal(letter, 'receivers')" class="btn btn-@{{ letter.receivers.length == (letter.informations | countCode:'receivers') ? 'default' : 'primary' }}" title="assign receivers">
                                    <span class="glyphicon glyphicon-envelope"></span>
                                    <span class="glyphicon glyphicon-arrow-right"></span>
                                    <span class="glyphicon glyphicon-user"></span>
                                </a>
                            </div>
                            <div ng-repeat="receiver in letter.receivers">@{{ receiver.name_2013 }}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">&nbsp;</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div ng-show="letter.from != null">@{{ letter.from.name }}</div>
                            <div ng-show="letter.from == null">
                                <a href ng-click="openLocationModal(letter, 'from')" class="btn btn-primary" title="assign from location">
                                    <span class="glyphicon glyphicon-map-marker"></span>
                                    <span class="glyphicon glyphicon-arrow-right"></span>
                                    <span class="glyphicon glyphicon-envelope"></span>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div ng-show="letter.to != null">@{{ letter.to.name }}</div>
                            <div ng-show="letter.to == null">
                                <a href ng-click="openLocationModal(letter, 'to')" class="btn btn-primary" title="assign to location">
                                    <span class="glyphicon glyphicon-envelope"></span>
                                    <span class="glyphicon glyphicon-arrow-right"></span>
                                    <span class="glyphicon glyphicon-map-marker"></span>
                                </a>
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
