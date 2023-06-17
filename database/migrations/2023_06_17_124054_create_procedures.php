<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $sql = <<<SQL

        -- Tworzenie procedury GetFavoriteActor
        CREATE PROCEDURE `GetFavoriteActor` (IN `p_user_id` INT)
            BEGIN
                DECLARE v_actor_id INT;
                DECLARE v_actor_name VARCHAR(100);

                -- Tymczasowa tabela przechowująca liczbę wystąpień aktorów w filmach wypożyczonych przez użytkownika
                CREATE TEMPORARY TABLE IF NOT EXISTS actor_counts (
                    actor_id INT,
                    appearances_count INT
                );

                -- Wypełnienie tabeli actor_counts danymi o liczbie wystąpień aktorów w filmach
                INSERT INTO actor_counts (actor_id, appearances_count)
                SELECT ma.actor_id, COUNT(*) AS appearances_count
                FROM orders o
                JOIN movies m ON o.movie_id = m.movie_id
                JOIN movies_actors_transfer ma ON m.movie_id = ma.movie_id
                WHERE o.user_id = p_user_id AND o.payment_status = 'succeed'
                GROUP BY ma.actor_id;

                -- Znalezienie aktora o największej liczbie wystąpień
                SELECT actor_id INTO v_actor_id
                FROM actor_counts
                WHERE appearances_count = (
                    SELECT MAX(appearances_count)
                    FROM actor_counts
                )
                LIMIT 1;

                -- Pobranie nazwy aktora na podstawie ID aktora z wyniku procedury
                SELECT actor_name INTO v_actor_name
                FROM actors
                WHERE actor_id = v_actor_id;

                -- Wyświetlenie informacji o ulubionym aktorze użytkownika
                SELECT CONCAT(' ', v_actor_name) AS result;

                -- Usunięcie tymczasowej tabeli
                DROP TEMPORARY TABLE IF EXISTS actor_counts;
            END;

        CREATE PROCEDURE `GetFavoriteCategory` (IN `p_user_id` INT)
            BEGIN
                -- Tymczasowa tabela przechowująca liczbę wypożyczeń w poszczególnych kategoriach dla danego użytkownika
                CREATE TEMPORARY TABLE IF NOT EXISTS category_counts (
                    category_id INT,
                    rentals_count INT
                );

                -- Wypełnienie tabeli category_counts danymi o liczbie wypożyczeń w poszczególnych kategoriach
                INSERT INTO category_counts (category_id, rentals_count)
                SELECT m.category_id, COUNT(*) AS rentals_count
                FROM orders o
                JOIN movies m ON o.movie_id = m.movie_id
                WHERE o.user_id = p_user_id AND o.payment_status = 'succeed'
                GROUP BY m.category_id;

                -- Znalezienie kategorii o największej liczbie wypożyczeń
                SELECT category_id
                FROM category_counts
                WHERE rentals_count = (
                    SELECT MAX(rentals_count) FROM category_counts
                )
                ORDER BY category_id
                LIMIT 1
                INTO @fav_category_id;

                -- Aktualizacja kolumny id_fav_category w tabeli users
                UPDATE users
                SET id_fav_category = @fav_category_id
                WHERE user_id = p_user_id;

                -- Usunięcie tymczasowej tabeli
                DROP TEMPORARY TABLE IF EXISTS category_counts;

                -- Zwrócenie wyniku
                SELECT @fav_category_id AS fav_category_id;
            END;

        CREATE PROCEDURE `GetFavoriteMovie` (IN `p_user_id` INT)
            BEGIN
                -- Znalezienie filmu o największej liczbie wypożyczeń dla danego użytkownika
                SELECT m.title
                FROM orders o
                JOIN movies m ON o.movie_id = m.movie_id
                WHERE o.user_id = p_user_id AND o.payment_status = 'succeed'
                GROUP BY o.movie_id
                ORDER BY COUNT(*) DESC
                LIMIT 1;
            END;

        CREATE PROCEDURE `GetMovieFromLastCategory` (IN `userId` INT)
            BEGIN
                DECLARE lastCategoryId INT;
                DECLARE randomMovieId INT;
                DECLARE category_name VARCHAR(255);

                -- Pobranie ostatnio wypożyczonej kategorii przez użytkownika
                SELECT movies.category_id INTO lastCategoryId
                FROM orders
                JOIN movies ON orders.movie_id = movies.movie_id
                WHERE orders.user_id = userId
                ORDER BY orders.rent_end DESC
                LIMIT 1;

                -- Pobranie ostatnio wypożyczonego filmu
                SET @lastRentedMovieId := (
                    SELECT movie_id
                    FROM orders
                    WHERE user_id = userId
                    ORDER BY rent_end DESC
                    LIMIT 1
                );

                -- Tworzenie tymczasowej tabeli
                CREATE TEMPORARY TABLE IF NOT EXISTS temp_movies (
                    movie_id INT
                );

                -- Wypełnianie tymczasowej tabeli filmami z ostatnio wypożyczonej kategorii, które nie są ostatnio wypożyczonym filmem
                INSERT INTO temp_movies (movie_id)
                SELECT movie_id
                FROM movies
                WHERE category_id = lastCategoryId
                AND movie_id <> @lastRentedMovieId;

                -- Wybór losowego filmu z tymczasowej tabeli
                SELECT movie_id INTO randomMovieId
                FROM temp_movies
                ORDER BY RAND()
                LIMIT 1;

                -- Pobranie nazwy kategorii
                SELECT category_name INTO category_name
                FROM categories
                WHERE category_id = lastCategoryId;

                -- Zwrócenie wyniku
                SELECT movies.*, category_name AS category_name
                FROM movies
                WHERE movie_id = randomMovieId;

                -- Usunięcie tymczasowej tabeli
                DROP TEMPORARY TABLE IF EXISTS temp_movies;
            END;

        CREATE PROCEDURE `GetTop3MoviesToday` ()
            BEGIN
                -- Zapytanie wybierające dzisiejszą datę
                SET @currentDate = CURRENT_DATE();

                -- Zapytanie wybierające 3 najpopularniejsze filmy z dzisiejszego dnia
                SELECT m.movie_id, m.title, m.img_path, COUNT(*) AS popularity
                FROM orders o
                JOIN movies m ON o.movie_id = m.movie_id
                WHERE DATE(o.rent_start) = @currentDate AND o.payment_status = 'succeed'
                GROUP BY m.movie_id, m.title, m.img_path
                ORDER BY popularity DESC
                LIMIT 3;
            END;

        SQL;

        DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS `GetFavoriteActor`');
        DB::unprepared('DROP PROCEDURE IF EXISTS `GetFavoriteCategory`');
        DB::unprepared('DROP PROCEDURE IF EXISTS `GetFavoriteMovie`');
        DB::unprepared('DROP PROCEDURE IF EXISTS `GetMovieFromLastCategory`');
        DB::unprepared('DROP PROCEDURE IF EXISTS `GetTop3MoviesToday`');
    }
};
