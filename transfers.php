<?php
require_once './incld/header.php';

?>
<!-- Header -->
<header class="w3-container w3-padding">
    <h2><i class="fa fa-exchange w3-xlarge"></i> Transfers</h2>
</header>

<!-- Edit Transfer Modal -->
<div class="modal fade" id="transferEditModal" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Edit Asset Transfer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateTransfer">
                <div class="modal-body">
                    <div id="errorMessageUpdate" class="alert alert-warning d-none">
                    </div>
                    <input type="hidden" name="id" id="id" />
                    <div class="mb-3">
                        <label for="date">Date</label>
                        <input type="date" name="date" id="date" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label for="descr">Description</label>
                        <input type="text" name="descr" id="descr" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label for="amount">Amount</label>
                        <input type="number" step="0.01" name="amount" id="amount" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label for="facct_name">FROM Account</label>
                        <?php
                        $sql = "SELECT category.id, category.cat_name, asset.asset_type
                          FROM category
                          INNER JOIN asset ON category.id = asset.cat_id
                          WHERE category.user_id = '$user_id'
                          ORDER BY cat_name ASC";
                        $result = mysqli_query($link, $sql);
                        echo "<select class='form-select' name='facct_name' id='facct_name'>";
                        echo "<option value='' disabled selected>FROM</option>";

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['cat_name']) . "</option>";
                        }
                        echo "</select></td>";
                        ?>
                    </div>
                    <div class="mb-3">
                        <label for="tacct_name">TO Account</label>
                        <?php
                        $sql = "SELECT category.id, category.cat_name, asset.asset_type
                          FROM category
                          INNER JOIN asset ON category.id = asset.cat_id
                          WHERE category.user_id = '$user_id'
                          ORDER BY cat_name ASC";
                        $result = mysqli_query($link, $sql);
                        echo "<select class='form-select' name='tacct_name' id='tacct_name'>";
                        echo "<option value='' disabled selected>TO</option>";
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['cat_name']) . "</option>";
                        }
                        echo "</select></td>";
                        ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Transfer Modal -->
<div class="modal fade" id="transferAddModal" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Transfer Assets</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="saveTransfer">
                <div class="modal-body">
                    <div id="errorMessage" class="alert alert-warning d-none">
                    </div>
                    <input type="hidden" name="id" id="id">
                    <div class="mb-3">
                        <label for="date">Date</label>
                        <input type="date" name="dateTra" id="dateTra" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label for="descr">Description</label>
                        <input type="text" name="descr" id="descr" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label for="amount">Amount</label>
                        <input type="number" step="0.01" name="amount" id="amount" class="form-control" placeholder="0.00" />
                    </div>
                    <div class="mb-3">
                        <label for="facct_name">FROM Account</label>
                        <?php
                        $sql = "SELECT category.id, category.cat_name, asset.asset_type
                          FROM category
                          INNER JOIN asset ON category.id = asset.cat_id
                          WHERE category.user_id = '$user_id'
                          ORDER BY cat_name ASC";
                        $result = mysqli_query($link, $sql);
                        echo "<select class='form-select' name='facct_name' id='facct_name'>";
                        echo "<option value='' disabled selected>FROM</option>";

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['cat_name']) . "</option>";
                        }
                        echo "</select></td>";
                        ?>
                    </div>
                    <div class="mb-3">
                        <label for="tacct_name">TO Account</label>
                        <?php
                        $sql = "SELECT category.id, category.cat_name, asset.asset_type
                          FROM category
                          INNER JOIN asset ON category.id = asset.cat_id
                          WHERE category.user_id = '$user_id'
                          ORDER BY cat_name ASC";
                        $result = mysqli_query($link, $sql);
                        echo "<select class='form-select' name='tacct_name' id='tacct_name'>";
                        echo "<option value='' disabled selected>TO</option>";
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['cat_name']) . "</option>";
                        }
                        echo "</select></td>";
                        ?>
                    </div>
                    <div class="mb-3">
                        <input class="w3-input w3-cell" type="hidden" name="user_id" value="<?php echo "$user_id"; ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Transfer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Transfers Table -->
<div class="w3-container w3-padding w3-margin" id="transfer">
    <button id="showhide" class="w3-btn w3-card-4 w3-block w3-round-large w3-left-align flat-blue-fade w3-border w3-border-gray">
        <h2>Transfer Assets</h2>
    </button>
    <p></p>
    <div id="show" class="w3-responsive w3-container w3-hide w3-padding noscrollbar">
        <div class="w3-left w3-padding">
            <button type="button" class="w3-btn w3-card-4 w3-border w3-round-large w3-border-gray w3-hover-light-gray flat-blue-fade" data-bs-toggle="modal" data-bs-target="#transferAddModal">+ Tansfer</button>
        </div>
        <!-- Table Year selection ----------------------------------------------------------->
        <div class="w3-right w3-padding w3-margin">
            <select class="w3-btn w3-border w3-card-4 w3-round-large w3-border-gray w3-hover-light-gray flat-blue-fade" id="tblTrsYr" data-width="100px">
                <?php
                $sqlquery = "SELECT DISTINCT YEAR(date) as Years
                           FROM transfers
                           WHERE user_id = '$user_id'
                           ORDER BY date DESC";
                $sqltran = mysqli_query($link, $sqlquery);

                echo "<option value='" . $curYear . "' selected>" . $curYear . "</option>";
                while ($rowList = mysqli_fetch_array($sqltran)) {
                    if ($rowList['Years'] !== $curYear) {
                        echo "<option value='" . htmlspecialchars($rowList["Years"]) . "'>" . htmlspecialchars($rowList["Years"]) . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="w3-mobile w3-padding w3-margin">
            <div class="w3-right w3-padding">
                <input id="tableSrch" type="text" placeholder="Filter..">
            </div>
        </div>
        <?php
        // display itemized list of tranfer entries from db table
        $sql = "SELECT t.id, t.date, t.descr, t.amount, t.facct_id, t.tacct_id, c.cat_name as facct_name, c2.cat_name as tacct_name
            FROM transfers AS t
            LEFT JOIN category AS c ON t.facct_id = c.id
            LEFT JOIN category AS c2 ON t.tacct_id = c2.id
            WHERE t.user_id = '$user_id'
            AND YEAR(t.date) = YEAR(now())
            ORDER BY t.date DESC";
        $result = mysqli_query($link, $sql);
        $data = $result->fetch_all(MYSQLI_ASSOC);
        ?>
        <div id="traTable">
            <table class="w3-table w3-bordered w3-hoverable-trnsfr flat-blue-fade w3-card-4" id="srtTable">
                <thead>
                    <tr class="w3-gray">
                        <th><a href="javascript:SortTable(0,'D','mdy');">Date <i class="fa fa-sort"></i></a></th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>FROM Acct</th>
                        <th>TO Acct</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tblSrch">
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <td><?= date("m-d-Y", strtotime($row['date'])) ?></td>
                            <td><?= htmlspecialchars($row['descr']) ?></td>
                            <td><?= number_format($row['amount'], 2) ?></td>
                            <td><?= htmlspecialchars($row['facct_name']) ?></td>
                            <td><?= htmlspecialchars($row['tacct_name']) ?></td>
                            <td>
                                <button type="button" value="<?= $row['id']; ?>" class="editTraBtn btn btn-success btn-sm">Edit</button>
                                <button type="button" value="<?= $row['id']; ?>" class="deleteTraBtn btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="./js/transjava.js"></script>
<?php

require_once './incld/footer.php';

?>
