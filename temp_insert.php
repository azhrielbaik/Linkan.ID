<?php

$sql = "INSERT INTO `users` (`id`, `name`, `username`, `custom_link`, `is_link_active`, `bio`, `avatar`, `email`, `google_id`, `email_verified_at`, `password`, `role`, `balance`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'mrifqi', 'mrap', NULL, 1, NULL, NULL, 'mrifqiap03@gmail.com', '105024033295951860033', NULL, '$2y$12$7W1lUyio.MkWxsP.LmY9quKsnegyBVHivw.0taiSEuSWZA8WfPQDy', 'admin_seller', 0.00, NULL, '2025-04-24 03:52:46', '2025-04-24 03:53:33', NULL),
(2, 'Fajar Ramadhan Ms', 'domaci', NULL, 1, NULL, NULL, 'dhefajar0410@gmail.com', '111927696868092660998', NULL, '$2y$12$cyRU/Fs5d62ljNZ3S.ETNODcfLhe3uaF39nRj/ne64Ww6pxiLQY6u', 'admin_seller', 0.00, NULL, '2025-04-30 02:17:17', '2025-04-30 22:41:28', NULL),
(3, 'rifqi', 'admin', NULL, 1, NULL, NULL, 'rachaugy123@gmail.com', NULL, NULL, '$2y$12$eiMWxfcJmZTfp6Mqvl4LTudloCt4BSfti4Hxxdz4YUA.fTjpMRm/C', 'admin_seller', 0.00, NULL, '2025-04-30 02:25:16', '2025-04-30 02:25:16', NULL),
(4, 'Ardy Damar', 'ardy12', NULL, 1, NULL, NULL, 'ardydamar22@gmail.com', '102511019137411583522', NULL, '$2y$12$v7x7Iog.kK.RPQxi1qntD.1ePTLl3VXoEHWsR17X5XF5gcfhILBwi', 'admin_seller', 0.00, NULL, '2025-05-02 19:08:12', '2025-05-03 01:41:18', NULL),
(5, 'Mohammad Azhriel', 'azhriel', NULL, 1, NULL, NULL, 'azhrielmuari@gmail.com', NULL, NULL, '$2y$12$5UYtEW9DptzzU81uBP2oQ.K2dfdGOD.PtbzWWAu.G3NAHMdV7nGdO', 'admin_seller', 0.00, NULL, '2026-08-02 21:49:43', '2026-08-02 21:49:43', NULL),
(6, 'Admin Platform', 'admin_platform', NULL, 1, NULL, NULL, 'admin@linkan.id', NULL, NULL, '$2y$12$ZTndjcS7Cd8RJsVpzVtCB.I9063M6MvcV5P2Sx/ISaqN79u5Gm4pW', 'admin_platform', 0.00, NULL, '2026-08-02 23:22:05', '2026-08-02 23:22:05', NULL)";

// Since we are running in tinker, we use single quotes for the PHP string to prevent variable interpolation
$sql = 'INSERT INTO `users` (`id`, `name`, `username`, `custom_link`, `is_link_active`, `bio`, `avatar`, `email`, `google_id`, `email_verified_at`, `password`, `role`, `balance`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, \'mrifqi\', \'mrap\', NULL, 1, NULL, NULL, \'mrifqiap03@gmail.com\', \'105024033295951860033\', NULL, \'$2y$12$7W1lUyio.MkWxsP.LmY9quKsnegyBVHivw.0taiSEuSWZA8WfPQDy\', \'admin_seller\', 0.00, NULL, \'2025-04-24 03:52:46\', \'2025-04-24 03:53:33\', NULL),
(2, \'Fajar Ramadhan Ms\', \'domaci\', NULL, 1, NULL, NULL, \'dhefajar0410@gmail.com\', \'111927696868092660998\', NULL, \'$2y$12$cyRU/Fs5d62ljNZ3S.ETNODcfLhe3uaF39nRj/ne64Ww6pxiLQY6u\', \'admin_seller\', 0.00, NULL, \'2025-04-30 02:17:17\', \'2025-04-30 22:41:28\', NULL),
(3, \'rifqi\', \'admin\', NULL, 1, NULL, NULL, \'rachaugy123@gmail.com\', NULL, NULL, \'$2y$12$eiMWxfcJmZTfp6Mqvl4LTudloCt4BSfti4Hxxdz4YUA.fTjpMRm/C\', \'admin_seller\', 0.00, NULL, \'2025-04-30 02:25:16\', \'2025-04-30 02:25:16\', NULL),
(4, \'Ardy Damar\', \'ardy12\', NULL, 1, NULL, NULL, \'ardydamar22@gmail.com\', \'102511019137411583522\', NULL, \'$2y$12$v7x7Iog.kK.RPQxi1qntD.1ePTLl3VXoEHWsR17X5XF5gcfhILBwi\', \'admin_seller\', 0.00, NULL, \'2025-05-02 19:08:12\', \'2025-05-03 01:41:18\', NULL),
(5, \'Mohammad Azhriel\', \'azhriel\', NULL, 1, NULL, NULL, \'azhrielmuari@gmail.com\', NULL, NULL, \'$2y$12$5UYtEW9DptzzU81uBP2oQ.K2dfdGOD.PtbzWWAu.G3NAHMdV7nGdO\', \'admin_seller\', 0.00, NULL, \'2026-08-02 21:49:43\', \'2026-08-02 21:49:43\', NULL),
(6, \'Admin Platform\', \'admin_platform\', NULL, 1, NULL, NULL, \'admin@linkan.id\', NULL, NULL, \'$2y$12$ZTndjcS7Cd8RJsVpzVtCB.I9063M6MvcV5P2Sx/ISaqN79u5Gm4pW\', \'admin_platform\', 0.00, NULL, \'2026-08-02 23:22:05\', \'2026-08-02 23:22:05\', NULL)';

DB::statement($sql);
echo "Data inserted successfully.";
