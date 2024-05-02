
<section class="admin-panel container">
    <h1 class="section-title">Twoje konto</h1>
    <div class="user-panel">
        <?php require_once __DIR__ . '/../includes/panelNav.php'?>
        <div class="content">
            <div class="d-flex align-items-center justify-content-end mb-2">
                <a href="/addRestaurant" title="Dodaj restaurację" class="button button-primary">Dodaj restaurację</a>
            </div>
            <table id="table-list">
                <tr>
                    <th>L.p.</th>
                    <th>Zdjęcie</th>
                    <th>Nazwa</th>
                    <th>Opcje</th>
                </tr>
                <?php $count = 0; foreach ($restaurants as $restaurant):  ?>
                    <tr id="row_<?= $restaurant->getId() ?>">
                        <td><?= ++$count ?></td>
                        <td><img src="/public/uploads/<?= $restaurant->getImage() ?>" alt="Dara kebab"></td>
                        <td><?= $restaurant->getName() ?></td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center">
                                <a href="/addRestaurant/<?= $restaurant->getId() ?>" class=""><i class="fa-solid fa-edit"></i></a>
                                <a href="#" data-id="<?= $restaurant->getId() ?>" class="publicate-btn <?= $restaurant->getPublicate() ? '' : 'no' ?>"><i class="fa-solid fa-eye"></i></a>
                                <a href="#" data-id="<?= $restaurant->getId() ?>" class="delete-btn"><i class="fa-solid fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</section>
<script src="/public/js/panel.js" defer></script>
