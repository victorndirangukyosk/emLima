<?php

class ModelCommonUpdate extends Model
{
    public function check()
    {
        // Fire event
        $this->trigger->fire('pre.admin.update.check');

        $this->cache->remove('addon');
        $this->cache->remove('update');
        $this->cache->remove('version');

        return true;
    }

    // Upgrade
    public function update()
    {
        $version = $this->request->get['version'];
        $product_id = $this->request->get['product_id'];

        $data = $this->update->downloadUpdate($product_id, $version);

        $path = 'temp-'.md5(mt_rand());
        $file = DIR_UPLOAD.$path.'/upload.zip';

        if (!is_dir(DIR_UPLOAD.$path)) {
            $this->filesystem->mkdir(DIR_UPLOAD.$path);
        }

        $uploaded = is_int(file_put_contents($file, $data)) ? true : false;

        if (!$uploaded) {
            return false;
        }

        // Fire event
        $this->trigger->fire('pre.admin.update.update', $product_id);

        // Force enable maintenance mode
        $maintenance_mode = $this->config->get('maintenance_mode');
        $this->config->set('maintenance_mode', 1);

        $installer = new Installer($this->registry);

        if (!$installer->unzip($file)) {
            return false;
        }

        // Remove Zip
        unlink($file);

        if ('core' == $product_id) {
            $temp_path = DIR_UPLOAD.$path;
            $install_path = $temp_path.'/install';

            // Load the update script, if available
            if (is_file($install_path.'/update.php')) {
                require_once $install_path.'/update.php';
            }

            // Don't copy the install folder
            $this->filesystem->remove($install_path);

            // Move all files/folders from temp path
            $this->filesystem->mirror($temp_path, DIR_ROOT, null, ['override' => true]);

            // Delete the temp path
            $this->filesystem->remove($temp_path);
        } else {
            // Required for ftp & remove extension functions
            $this->request->post['path'] = $path;

            $ftp = $this->load->controller('extension/installer/ftp');
            $remove = $this->load->controller('extension/installer/remove');

            $this->db->query('UPDATE `'.DB_PREFIX."addon` SET `product_version` = '".$this->db->escape($version)."' WHERE `product_id` = '".(int) $product_id."'");
        }

        // Restore maintenance mode
        $this->config->set('maintenance_mode', $maintenance_mode);

        // Fire event
        $this->trigger->fire('post.admin.update.update', $product_id);

        return true;
    }
}
