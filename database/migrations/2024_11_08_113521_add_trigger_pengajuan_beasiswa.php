<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddTriggerPengajuanBeasiswa extends Migration
{
    public function up()
    {
        // Procedure to update scholarship submission status
        DB::statement("
            CREATE OR REPLACE PROCEDURE update_status_pengajuan(
                p_id INTEGER,
                p_status INTEGER
            )
            LANGUAGE plpgsql AS $$
            BEGIN
                IF p_status NOT IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10) THEN
                    RAISE EXCEPTION 'Invalid status. Only allowed statuses are in kode_status.';
                END IF;

                UPDATE pengajuan_beasiswa
                SET status = p_status,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id_pengajuan_beasiswa = p_id;

                IF NOT FOUND THEN
                    RAISE NOTICE 'Submission with ID % not found.', p_id;
                ELSE
                    RAISE NOTICE 'Status for submission ID % updated to %.', p_id, p_status;
                END IF;
            END;
            $$;
        ");

        // Procedure to insert submission with document JSON array
        DB::statement("
            CREATE OR REPLACE PROCEDURE insert_pengajuan_beasiswa_with_dokumen(
                pnim VARCHAR,
                pbeasiswa_id INT,
                pdokumen JSONB[],
                pstatus INTEGER
            )
            LANGUAGE plpgsql AS $$
            DECLARE
                edokumen JSONB;
                nama_dokumen VARCHAR;
                link_dokumen VARCHAR;
                kode_dokumen VARCHAR;
                pengajuan_id INT;
            BEGIN
                INSERT INTO pengajuan_beasiswa (nim, beasiswa_id, tanggal_pengajuan, status)
                VALUES (pnim, pbeasiswa_id, NOW(), pstatus)
                RETURNING id INTO pengajuan_id;

                FOREACH edokumen IN ARRAY pdokumen LOOP
                    nama_dokumen := edokumen->>'nama_dokumen';
                    link_dokumen := edokumen->>'link_dokumen';
                    kode_dokumen := edokumen->>'kode_dokumen';

                    IF nama_dokumen IS NULL OR nama_dokumen = '' THEN
                        RAISE EXCEPTION 'Error: nama_dokumen is required for each document';
                    ELSIF link_dokumen IS NULL OR link_dokumen = '' THEN
                        RAISE EXCEPTION 'Error: link_dokumen is required for each document';
                    ELSIF kode_dokumen IS NULL OR kode_dokumen = '' THEN
                        RAISE EXCEPTION 'Error: kode_dokumen is required for each document';
                    END IF;

                    INSERT INTO dokumen (id_pengajuan_beasiswa, nama_dokumen, link_dokumen, kode_dokumen)
                    VALUES (pengajuan_id, nama_dokumen, link_dokumen, kode_dokumen);
                END LOOP;
            EXCEPTION
                WHEN OTHERS THEN
                    RAISE NOTICE 'Error while inserting submission and documents: %', SQLERRM;
                    RAISE;
            END;
            $$;
        ");

        // Trigger function for document submission notification
        DB::statement("
            CREATE OR REPLACE FUNCTION kirim_notif_saat_dokumen_masuk()
            RETURNS TRIGGER
            LANGUAGE PLPGSQL AS $$
            DECLARE
                user_id_mahasiswa INTEGER;
                user_id_reviewer INTEGER;
            BEGIN
                SELECT user_id INTO user_id_mahasiswa
                FROM mahasiswa
                WHERE nim = NEW.nim;

                INSERT INTO notifikasi(user_id, id_pengajuan_beasiswa, status)
                VALUES (user_id_mahasiswa, NEW.id, 1);

                FOR user_id_reviewer IN
                    SELECT user_id
                    FROM reviewer
                    WHERE role_id = 1
                LOOP
                    INSERT INTO notifikasi(user_id, id_pengajuan_beasiswa, status)
                    VALUES (user_id_reviewer, NEW.id, 1);
                END LOOP;

                RETURN NEW;
            END;
            $$;
        ");

        // Trigger for document submission notification
        DB::statement("
            CREATE TRIGGER kirim_notif_saat_dokumen_masuk
            AFTER INSERT ON pengajuan_beasiswa
            FOR EACH ROW
            EXECUTE FUNCTION kirim_notif_saat_dokumen_masuk();
        ");

        // Trigger function for status update notification
        DB::statement("
    CREATE OR REPLACE FUNCTION kirim_notif_saat_update_status()
    RETURNS TRIGGER
    LANGUAGE PLPGSQL AS $$
    DECLARE
        user_id_reviewer INTEGER;
        user_id_mahasiswa INTEGER;
        lid_prodi INTEGER;
        lid_jurusan INTEGER;
        lrole_id INTEGER;
    BEGIN
        -- Assigning the role ID based on the status
        CASE NEW.status
            WHEN 1 THEN lrole_id := 1;
            WHEN 2 THEN lrole_id := 1;
            WHEN 3 THEN lrole_id := 1;
            WHEN 4 THEN lrole_id := 2;
            WHEN 5 THEN lrole_id := 2;
            WHEN 6 THEN lrole_id := 3;
            WHEN 7 THEN lrole_id := 3;
            WHEN 8 THEN lrole_id := 4;
            WHEN 9 THEN lrole_id := 4;
            WHEN 10 THEN lrole_id := NULL;
            WHEN 11 THEN lrole_id := NULL;
            ELSE lrole_id := NULL;
        END CASE;

        -- Only proceed if role ID is not null
        IF lrole_id IS NOT NULL THEN
            IF lrole_id = 2 THEN
                -- Fetch the program ID (prodi), department ID (jurusan), and reviewer (kajur_id)
                SELECT prodi_id INTO lid_prodi FROM mahasiswa WHERE mahasiswa.nim = NEW.nim;
                SELECT jurusan_id INTO lid_jurusan FROM prodi WHERE prodi.id = lid_prodi;
                SELECT kajur_id INTO user_id_reviewer FROM jurusan WHERE jurusan.id = lid_jurusan;

                -- Insert notification for the department head
                INSERT INTO notifikasi(user_id, id_pengajuan_beasiswa, status)
                VALUES (user_id_reviewer, NEW.id, NEW.status);
            ELSE
                -- Notify all reviewers with the corresponding role ID
                FOR user_id_reviewer IN
                    SELECT user_id
                    FROM reviewer
                    WHERE reviewer.role_id = lrole_id
                LOOP
                    INSERT INTO notifikasi(user_id, id_pengajuan_beasiswa, status)
                    VALUES (user_id_reviewer, NEW.id, NEW.status);
                END LOOP;
            END IF;

            -- Notify the student
            SELECT user_id INTO user_id_mahasiswa FROM mahasiswa WHERE mahasiswa.nim = NEW.nim;
            INSERT INTO notifikasi(user_id, id_pengajuan_beasiswa, status)
            VALUES (user_id_mahasiswa, NEW.id, NEW.status);
        END IF;

        -- Return the updated record
        RETURN NEW;
    END;
    $$;
");


        // Trigger for status update notification
        DB::statement("
            CREATE TRIGGER kirim_notif_saat_update_status
            AFTER UPDATE OF status
            ON pengajuan_beasiswa
            FOR EACH ROW
            WHEN (OLD.status IS DISTINCT FROM NEW.status)
            EXECUTE FUNCTION kirim_notif_saat_update_status();
        ");
    }

    public function down()
    {
        // Drop the triggers and functions if rolling back
        DB::statement("DROP TRIGGER IF EXISTS kirim_notif_saat_dokumen_masuk ON pengajuan_beasiswa;");
        DB::statement("DROP FUNCTION IF EXISTS kirim_notif_saat_dokumen_masuk();");
        DB::statement("DROP TRIGGER IF EXISTS kirim_notif_saat_update_status ON pengajuan_beasiswa;");
        DB::statement("DROP FUNCTION IF EXISTS kirim_notif_saat_update_status();");
        DB::statement("DROP PROCEDURE IF EXISTS update_status_pengajuan(INTEGER, INTEGER);");
        DB::statement("DROP PROCEDURE IF EXISTS insert_pengajuan_beasiswa_with_dokumen(VARCHAR, INTEGER, JSONB[], INTEGER);");
    }
}
