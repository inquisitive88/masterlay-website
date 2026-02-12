            <!-- Page Content End -->
        </div>
    </div>

    <!-- Admin JS -->
    <script src="/admin/assets/js/admin.js?v=<?= time() ?>"></script>

    <?php if (!empty($adminExtraJs)): ?>
        <?php foreach ($adminExtraJs as $js): ?>
            <script src="<?= $js ?>?v=<?= time() ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
