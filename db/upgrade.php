<?php

function xmldb_block_grade_upgrade(int $oldVersion): bool {
    global $DB;

    $dbman = $DB->get_manager();

    // Upgrade steps here

    return true;
}
