<?php
ob_start();
?>
<div class="container-fluid">
    <div id="showFRM">
        <div class="row">
            <div class="col-md-12">
                <div class="card p-2 mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title">Forum </h4>
                                <!-- <p class="card-category"> xx</p> -->
                            </div>


                        </div>
                    </div>
                    <div class="card-body">
                        <table name="table" class="table table-hover table-head-bg-info my-2">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center" style="width: 50px;">No.</th>
                                    <th scope="col" class="text-center" style="width: 500px;">Judul</th>
                                    <th scope="col" class="text-center" style="width: 200px;">Aksi</th>
                                    <!-- <th scope="col" class="text-center">Aksi</th> -->
                                </tr>
                            </thead>
                            <tbody id="tBodyFRM">
                                <?php
                                $i = 1;
                                if (empty($forum)) {
                                    echo '<tr><td colspan="6" class="text-center">Belum ada data</td></tr>';
                                } else {
                                    foreach ($forum as $t) {

                                ?>
                                        <tr>
                                            <td><?php echo $i ?></td>
                                            <td><?php echo $t->FRM_TITLE ?></td>

                                            <th class="text-center"><a href="javascript:void(0)" onclick="showDetailFRM(<?php echo $t->FRM_ID ?>)" class="btn btn-primary"></i>Detail</a></th>
                                        </tr>
                                <?php
                                        $i++;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="readFRM" style="display: none;">
        <div class="col-md-12">
            <div class="card p-2">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <div class="card-title" id="title"></div>
                            <p class="card-category" id="publishDate"></p>
                        </div>

                    </div>
                </div>
                <div class="card-body" style="border-bottom: 1px solid #ebedf2 !important;">
                    <div class="row">
                        <div class="col-md-12">
                            <p id="desc"></p>
                        </div>
                    </div>
                    <div class="card-body" id="divBackFRM">
                        <a href="javascript:void(0)" onclick="changeFormFRM('main')" class="btn btn-danger"></i> Kembali</a>
                    </div>
                </div>
            </div>
            </form>
        </div>

    </div>


</div>
<script>
    async function showDetailFRM(id) {
        if (id != '0') {

            fetch('<?= base_url('Article/showDetail2/') ?>' + id)
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    console.log(data);
                    var dataFRM = data.dataFRM;

                    if (dataFRM) {
                        //   document.getElementById('idFRM').value = dataFRM.FRM_ID || '';
                        document.getElementById('title').innerHTML = dataFRM.FRM_TITLE || '';
                        document.getElementById('desc').innerHTML = dataFRM.FRM_DESC || '';
                        document.getElementById('publishDate').innerHTML = "Dipublikasi pada" + dataFRM.FRM_MODIDATE || '';


                        document.getElementById("showFRM").style.display = 'none';
                        document.getElementById("readFRM").style.display = 'block';

                    } else {
                        console.error('Error: No data found for id ' + id);
                    }
                })
                .catch(error => {
                    console.error('Error fetching data showdetail:', error);
                });
        }
    }

    function changeFormFRM() {
        document.getElementById("showFRM").style.display = 'block';
        document.getElementById("readFRM").style.display = 'none';

    }
</script>
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolder = ob_get_contents();
/* Clean out the buffer, and destroy the output buffer */
ob_end_clean();
/* Call the master page. It will echo the content of the placeholders in the designated locations */
include __DIR__ . "/layout.php";
?>