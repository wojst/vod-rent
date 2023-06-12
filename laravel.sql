-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Cze 12, 2023 at 06:21 PM
-- Wersja serwera: 10.4.28-MariaDB
-- Wersja PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel`
--

DELIMITER $$
--
-- Procedury
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAllMovies` ()   BEGIN
    SELECT movie_id, title, description, director, category_id, release_year, price, rentals_count, img_path
    FROM movies;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetFavoriteActor` (IN `p_user_id` INT)   BEGIN
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
    WHERE o.user_id = p_user_id
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetFavoriteCategory` (IN `p_user_id` INT)   BEGIN
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
    WHERE o.user_id = p_user_id
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetFavoriteMovie` (IN `p_user_id` INT)   BEGIN
    -- Znalezienie filmu o największej liczbie wypożyczeń dla danego użytkownika
    SELECT m.title
    FROM orders o
    JOIN movies m ON o.movie_id = m.movie_id
    WHERE o.user_id = p_user_id
    GROUP BY o.movie_id
    ORDER BY COUNT(*) DESC
    LIMIT 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetMovieFromLastCategory` (IN `userId` INT)   BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetTop3MoviesToday` ()   BEGIN
    -- Zapytanie wybierające dzisiejszą datę
    SET @currentDate = CURRENT_DATE();
    
    -- Zapytanie wybierające 3 najpopularniejsze filmy z dzisiejszego dnia
    SELECT m.movie_id, m.title, m.img_path, COUNT(*) AS popularity
    FROM orders o
    JOIN movies m ON o.movie_id = m.movie_id
    WHERE DATE(o.rent_start) = @currentDate
    GROUP BY m.movie_id, m.title, m.img_path
    ORDER BY popularity DESC
    LIMIT 3;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `actors`
--

CREATE TABLE `actors` (
  `actor_id` bigint(20) UNSIGNED NOT NULL,
  `actor_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `actors`
--

INSERT INTO `actors` (`actor_id`, `actor_name`) VALUES
(1, 'Marlon Brando'),
(2, 'Tom Hanks'),
(3, 'Brad Pitt'),
(4, 'Matt Damon'),
(5, 'Al Pacino'),
(6, 'Edward Norton'),
(7, 'Russell Crowe'),
(8, 'Joaquin Phoenix'),
(9, 'Keanu Reeves'),
(10, 'Will Smith'),
(11, 'Ryan Reynolds'),
(12, 'Martin Lawrence'),
(13, 'Leonardo Di Caprio'),
(14, 'Kate Winslet'),
(15, 'Tom Hardy'),
(16, 'Bradley Cooper'),
(17, 'Zach Galifianakis'),
(18, 'Morgan Freeman'),
(19, 'Mark Ruffalo'),
(20, 'Jonah Hill'),
(21, 'Robert De Niro'),
(22, 'Robert Downey Jr.'),
(23, 'Angelina Jolie'),
(24, 'Joe Pesci'),
(25, 'Jodie Foster'),
(27, 'Jean Reno'),
(28, 'Natalie Portman'),
(29, 'Liam Neeson'),
(30, 'Robert Duvall'),
(31, 'Ben Affleck'),
(32, 'Christian Bale'),
(33, 'Josh Hartnett'),
(34, 'Johnny Depp'),
(35, 'Jack Nicholson'),
(36, 'Jim Carrey'),
(37, 'Tom Cruise'),
(39, 'Cameron Diaz'),
(41, 'Anthony Hopkins'),
(42, 'Hugh Jackman'),
(43, 'Woody Harrelson');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `categories`
--

CREATE TABLE `categories` (
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Dramat'),
(2, 'Wojenny'),
(3, 'Gangsterski'),
(4, 'Komedia'),
(5, 'Thriller'),
(6, 'Sci-Fi'),
(7, 'Horror'),
(8, 'Western'),
(9, 'Kryminał'),
(10, 'Akcja');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(2, '2019_08_19_000000_create_failed_jobs_table', 1),
(3, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(4, '2023_05_24_201625_create_actors_table', 1),
(5, '2023_05_24_201652_create_categories_table', 1),
(6, '2023_05_24_201724_create_movies_table', 1),
(7, '2023_05_24_201839_create_movies_actors_transfers_table', 1),
(8, '2023_05_26_204322_create_users_table', 1),
(9, '2023_05_26_211434_create_orders_table', 1),
(10, '2023_05_31_170911_add_price_to_movies', 2),
(11, '2023_05_31_185708_add_code_to_orders_table', 3);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `movies`
--

CREATE TABLE `movies` (
  `movie_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `director` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `release_year` year(4) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `rentals_count` int(11) NOT NULL DEFAULT 0,
  `img_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`movie_id`, `title`, `description`, `director`, `category_id`, `release_year`, `price`, `rentals_count`, `img_path`) VALUES
(1, 'Ojciec Chrzestny', 'Opowieść o nowojorskiej rodzinie mafijnej. Starzejący się Don Corleone pragnie przekazać władzę swojemu synowi.', 'Francis Ford Coppola', 3, '1972', 22.00, 13, 'img/movies/godfather.jpg'),
(2, 'Szeregowiec Ryan', 'W poszukiwaniu zaginionego szeregowca wysłany zostaje doborowy oddział żołnierzy. Czy uda się im wykonać zadanie?', 'Steven Spielberg', 2, '1998', 12.00, 6, 'img/movies/savingprivateryan.jpg'),
(3, 'Podziemny Krąg', 'Cierpiący na bezsenność mężczyzna poznaje gardzącego konsumpcyjnym stylem życia Tylera Durdena, który jest jego zupełnym przeciwieństwem.', 'David Fincher', 5, '1999', 15.00, 2, 'img/movies/fightclub.jpg'),
(4, 'Gladiator', 'Generał Maximus - prawa ręka cesarza, szczęliwy mąż i ojciec - w jednej chwili traci wszystko. Jako niewolnik-gladiator musi walczyć na arenie o przeżycie.', 'Ridley Scott', 1, '2000', 11.00, 11, 'img/movies/gladiator.jpg'),
(5, 'Matrix', 'Haker komputerowy Neo dowiaduje się od tajemniczych rebeliantów, że świat, w którym żyje, jest tylko obrazem przesyłanym do jego mózgu przez roboty.', 'Lily Wachowski', 6, '1999', 18.00, 9, 'img/movies/matrix.jpg'),
(6, 'Zielona mila', 'Emerytowany strażnik więzienny opowiada przyjaciółce o niezwykłym męczyźnie, którego skazano na śmierć za zabójstwo dwóch 9-letnich dziewczynek.', 'Frank Darabont', 1, '1999', 18.00, 5, 'img/movies/zielonamila.jpg'),
(7, 'Bad Boys', 'Marcus i Mike, policjanci z wydziału antynarkotykowego, mają trzy dni na odzyskanie skradzionej z depozytu heroiny wartej 100 mln dolarów.', 'Michael Bay', 10, '1995', 12.00, 5, 'img/movies/badboys.jpg'),
(8, 'Deadpool 2', 'Oszpecony przez eksperymenty Deadpool nadal walczy ze złem na swój niepowtarzalny sposób.', 'David Leitch', 10, '2018', 14.00, 6, 'img/movies/deadpool2.jpg'),
(9, 'Titanic', 'Rok 1912, brytyjski statek Titanic wyrusza w swój dziewiczy rejs do USA. Na pokładzie emigrant Jack przypadkowo spotyka arystokratkę Rose.', 'James Cameron', 1, '1997', 13.00, 3, 'img/movies/titanic.jpg'),
(10, 'Incepcja', 'Czasy, gdy technologia pozwala na wchodzenie w świat snów. Złodziej Cobb ma za zadanie wszczepić myśl do śpiącego umysłu.', 'Christopher Nolan', 6, '2010', 18.00, 1, 'img/movies/incepcja.jpg'),
(11, 'Siedem Dusz', 'Mężczyzna przedstawiający się jako urzędnik podatkowy pojawia się w domach śmiertelnie chorych dłużników. Ma dla nich dar.', 'Gabriele Muccino', 1, '2008', 16.00, 3, 'img/movies/siedemdusz.jpg'),
(12, 'Kac Vegas', 'Przyjaciele spędzają wieczór kawalerski w Las Vegas. Następnego dnia okazuje się, że zgubili pana młodego.', 'Todd Phillips', 4, '2009', 13.00, 6, 'img/movies/kacvegas.jpg'),
(13, 'Siedem', 'Dwóch policjantów stara się złapać seryjnego mordercę wybierającego swoje ofiary według specjalnego klucza.', 'David Fincher', 9, '1995', 16.00, 3, 'img/movies/siedem.jpg'),
(14, 'Bad Boys 2', 'Dwóch agentów jednostki antynarkotykowej z Miami stawia czoła bezwzględnemu przemytnikowi ecstasy.', 'Michael Bay', 10, '2003', 12.00, 2, 'img/movies/badboys2.jpg'),
(15, 'Wyspa Tajemnic', 'Szeryf stara się dowiedzieć, jak z celi w strzeżonym szpitalu dla chorych psychicznie przestępców zniknęła pacjentka.', 'Martin Scorsese', 5, '2010', 19.00, 5, 'img/movies/wyspatajemnic.jpg'),
(16, 'Wilk z Wall Street', 'Historia Jordana Belforta, którego błyskawiczna droga na szczyt wzbudziły zainteresowanie FBI.', 'Martin Scorsese', 4, '2013', 17.00, 7, 'img/movies/wilkzwallstreet.jpg'),
(17, 'Joker', 'Strudzony życiem komik popada w obłęd i staje się psychopatycznym mordercą.', 'Todd Phillips', 10, '2019', 18.00, 7, 'img/movies/joker.jpg'),
(18, 'Sherlock Holmes', 'Detektyw Sherlock Holmes z przyjacielem dr. Watsonem szukają sprawcy rytualnych morderstw.', 'Guy Ritchie', 9, '2009', 18.00, 3, 'img/movies/sherlockholmes.jpg'),
(19, 'Iron Man', 'Tony Stark buduje supernowoczesną zbroję. Multimiliarder postanawia walczyć ze złem jako Iron Man.', 'Jon Favreau', 10, '2008', 12.00, 1, 'img/movies/ironman.jpg'),
(20, 'Mr. & Mrs. Smith', 'Para płatnych zabójców wiedzie monotonne małżeńskie życie. Niespodziewanie dostają zlecenie na siebie nawzajem.', 'Doug Liman', 10, '2005', 15.00, 3, 'img/movies/mrmssmith.jpg'),
(21, 'Krwawy Diament', 'Przemytnik z RPA i rybak wyruszają na poszukiwanie diamentu. Tłem akcji jest wojna domowa w Sierra Leone.', 'Edward Zwick', 1, '2006', 15.00, 2, 'img/movies/krwawydiament.jpg'),
(22, 'Ojciec Chrzestny II', 'Rok 1917. Młody Vito Corleone stawia pierwsze kroki w mafijnym Nowego Jorku. 40 lat później jego syn Michael walczy o interesy.', 'Francis Ford Coppola', 3, '1974', 20.00, 1, 'img/movies/godfather2.jpg'),
(23, 'Chłopcy z ferajny', 'Kilkunastoletni Henry i Tommy DeVito trafiają pod opiekę potęnego gangstera.', 'Martin Scorsese', 3, '1990', 19.50, 2, 'img/movies/chlopcyzferajny.jpg'),
(24, 'Deadpool', 'Były żołnierz oddziałów specjalnych zostaje poddany niebezpiecznemu eksperymentowi. Niebawem uwalnia swoje alter ego.', 'Tim Miller', 10, '2016', 14.00, 2, 'img/movies/deadpool.jpg'),
(25, 'Iron Man 2', 'Iron Man stawia czoło Iwanowi Wanko, znanemu jako Whiplash.', 'Jon Favreau', 10, '2010', 13.00, 4, 'img/movies/ironman2.jpg'),
(37, 'Leon Zawodowiec', 'Płatny morderca ratuje dwunastoletnią dziewczynkę, której rodzina została zabita przez skorumpowanych policjantów.', 'Luc Besson', 1, '1994', 20.00, 1, 'img/movies/leonzawodowiec.jpg'),
(38, 'Cast Away - poza światem', 'W wyniku katastrofy lotniczej inżynier firmy kurierskiej trafia na bezludną wyspę, gdzie musi przeżyć.', 'Robert Zemeckis', 1, '2000', 15.00, 0, 'img/movies/castaway.jpg'),
(39, 'Taksówkarz', 'Nowojorski taksówkarz Travis Bickle podczas nocnej zmiany poznaje młodocianą prostytutkę, której pragnie pomóc.', 'Martin Scorsese', 1, '1976', 16.00, 1, 'img/movies/taksowkarz.jpg'),
(40, 'Bękarty wojny', 'W okupowanej przez nazistów Francji oddział złożony z Amerykanów żydowskiego pochodzenia planuje zamach na Hitlera.', 'Quentin Tarantino', 2, '2009', 17.00, 0, 'img/movies/bekartywojny.jpg'),
(41, 'Mroczny Rycerz', 'Batman, z pomocą porucznika Gordona oraz prokuratora Harveya Denta, występuje przeciwko przerażającemu i nieobliczalnemu Jokerowi, który chce pogrążyć Gotham City w chaosie.', 'Christopher Nolan', 6, '2008', 16.00, 1, 'img/movies/mrocznyrycerz.jpg'),
(42, 'Snajper', 'Historia kariery wojskowej i życia osobistego Chrisa Kyle\'a, który zyskał sławę najlepszego snajpera w historii elitarnej jednostki Navy SEALs.', 'Clint Eastwood', 2, '2014', 16.00, 1, 'img/movies/snajper.jpg'),
(43, 'Pearl Harbor', 'Danny i Rafe są pilotami w czasie II wojny światowej. Gorące uczucie połączy obu z pielęgniarką Evelyn.', 'Michael Bay', 2, '2001', 16.00, 0, 'img/movies/pearlharbor.jpg'),
(44, 'Człowiek z blizną', 'Rok 1980. Pochodzący z Kuby przestępca, Tony Montana, tworzy w Miami narkotykowe imperium.', 'Brian De Palma', 3, '1983', 17.00, 1, 'img/movies/czlowiekzblizna.jpg'),
(45, 'Donnie Brasco', 'Film oparty na faktach. Agent FBI pod pseudonimem Donnie Brasco wnika w środowisko mafijne, by rozpracować je od środka.', 'Mike Newell', 3, '1997', 15.00, 0, 'img/movies/donniebrasco.jpg'),
(46, 'Choć goni nas czas', 'Historia dwóch umierających mężczyzn, którzy pod wpływem szalonych przygód zostają przyjaciółmi.', 'Rob Reiner', 4, '2007', 12.00, 3, 'img/movies/chocgoninasczas.jpg'),
(47, 'Maska', 'Stanley Ipkiss, nudny urzędnik bankowy, znajduje w rzece maskę. Gdy ją zakłada, staje się niezniszczalny, uwodzicielski i pewny siebie.', 'Chuck Russell', 4, '1994', 15.00, 3, 'img/movies/maska.jpg'),
(48, 'Ace Ventura: Psi detektyw', 'Detektyw specjalizujący się w odnajdywaniu zaginionych zwierząt poszukuje ukradzionego delfina – maskotki drużyny futbolowej.', 'Tom Shadyac', 4, '1994', 10.00, 3, 'img/movies/aceventurapsidetektyw.jpg'),
(49, 'Sekretne okno', 'Uznany pisarz przenosi się na prowincję, by w spokoju tworzyć kolejne książki. Wkrótce odwiedzi go tajemniczy mężczyzna, który oskarży Raineya o plagiat.', 'David Koepp', 5, '2004', 13.00, 0, 'img/movies/sekretneokno.jpg'),
(50, 'Hannibal', 'Dr Hannibal Lecter wraca do Ameryki, by odnowić kontakt z agentką Starling. Jego śladem podąża okaleczony przez niego i pałający żądzą zemsty Mason Verger.', 'Ridley SCott', 5, '2001', 16.00, 0, 'img/movies/hannibal.jpg'),
(51, 'Jestem Legendą', 'Tajemniczy wirus wymordował lub zamienił w krwiożercze bestie prawie całą ludzkość. Samotny naukowiec Robert Neville poszukuje szczepionki, by odwrócić mutację.', 'Francis Lawrence', 6, '2007', 15.00, 4, 'img/movies/jestemlegenda.jpg'),
(52, 'V jak Vendetta', 'W totalitarnej Anglii jedyną osobą walczącą o wolność jest bojownik przebrany za Guya Fawkesa – V, który pewnego dnia uwalnia z rąk agentów rządowych młodą kobietę.', 'James McTeigue', 6, '2005', 13.00, 0, 'img/movies/vjakvendetta.jpg'),
(53, 'Iluzja', 'Arcymistrzowie estradowych sztuczek wykorzystują iluzję do rabowania banków.', 'Louis Leterrier', 9, '2013', 12.00, 0, 'img/movies/iluzja.jpg'),
(54, 'Sędzia', 'Chicagowski adwokat Hank Palmer wraca do rodzinnego miasteczka w Indianie na pogrzeb matki. Niebawem skłócony z nim ojciec, szanowany lokalny sędzia, zostaje oskarżony o morderstwo.', 'David Dobkin', 9, '2014', 14.00, 1, 'img/movies/sedzia.jpg');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `movies_actors_transfer`
--

CREATE TABLE `movies_actors_transfer` (
  `movie_id` bigint(20) UNSIGNED NOT NULL,
  `actor_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `movies_actors_transfer`
--

INSERT INTO `movies_actors_transfer` (`movie_id`, `actor_id`) VALUES
(2, 2),
(2, 4),
(3, 3),
(3, 6),
(4, 7),
(4, 8),
(5, 9),
(6, 2),
(7, 10),
(8, 11),
(9, 13),
(9, 14),
(10, 13),
(10, 15),
(11, 10),
(12, 16),
(12, 17),
(13, 3),
(13, 18),
(14, 10),
(14, 12),
(15, 13),
(15, 19),
(16, 13),
(16, 20),
(17, 8),
(17, 21),
(18, 22),
(19, 22),
(20, 3),
(20, 23),
(21, 13),
(22, 5),
(22, 21),
(23, 21),
(23, 24),
(24, 11),
(25, 22),
(1, 1),
(7, 12),
(37, 27),
(37, 28),
(38, 2),
(39, 21),
(39, 25),
(40, 3),
(41, 32),
(1, 30),
(42, 16),
(43, 31),
(43, 33),
(44, 5),
(45, 5),
(45, 34),
(46, 18),
(46, 35),
(47, 36),
(47, 39),
(48, 36),
(49, 34),
(50, 41),
(51, 10),
(52, 28),
(53, 19),
(53, 43),
(54, 22),
(54, 30);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `orders`
--

CREATE TABLE `orders` (
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `movie_id` bigint(20) UNSIGNED NOT NULL,
  `rent_start` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `rent_end` timestamp NOT NULL DEFAULT (`rent_start` + interval 24 hour),
  `cost` decimal(8,2) NOT NULL,
  `code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `movie_id`, `rent_start`, `rent_end`, `cost`, `code`) VALUES
(2, 3, 2, '2023-05-31 16:59:24', '2023-06-01 16:59:24', 12.00, 'SOZ7C'),
(3, 3, 2, '2023-05-31 17:05:12', '2023-06-01 17:05:12', 12.00, '9CNJ1'),
(4, 3, 2, '2023-05-31 17:33:11', '2023-06-01 17:33:11', 12.00, '9A14Z'),
(5, 3, 1, '2023-05-31 17:34:09', '2023-06-01 17:34:09', 18.00, '89AAX'),
(6, 3, 1, '2023-05-31 17:34:15', '2023-06-01 17:34:15', 18.00, 'ZBQ9W'),
(8, 3, 4, '2023-05-31 17:34:29', '2023-06-01 17:34:29', 11.00, 'UYFTB'),
(9, 3, 4, '2023-05-31 17:34:33', '2023-06-01 17:34:33', 11.00, 'RINA9'),
(10, 3, 4, '2023-05-31 17:34:34', '2023-06-01 17:34:34', 11.00, 'ZPU5J'),
(11, 3, 4, '2023-05-31 17:34:36', '2023-06-01 17:34:36', 11.00, 'SBWSF'),
(12, 3, 4, '2023-05-31 17:34:37', '2023-06-01 17:34:37', 11.00, '0KRDG'),
(13, 3, 4, '2023-05-31 17:34:56', '2023-06-01 17:34:56', 11.00, 'A6EY6'),
(14, 3, 4, '2023-05-31 19:22:40', '2023-06-01 19:22:40', 11.00, '867UF'),
(15, 1, 2, '2023-05-31 19:26:05', '2023-06-01 19:26:05', 12.00, 'GL9P4'),
(16, 4, 21, '2023-06-02 05:58:57', '2023-06-03 05:58:57', 15.00, 'D59MP'),
(17, 4, 5, '2023-06-02 05:59:13', '2023-06-03 05:59:13', 18.00, 'ZM4EI'),
(18, 4, 8, '2023-06-02 05:59:24', '2023-06-03 05:59:24', 14.00, '063QT'),
(19, 4, 23, '2023-06-02 05:59:32', '2023-06-03 05:59:32', 19.50, 'IWZ3A'),
(20, 4, 17, '2023-06-02 05:59:58', '2023-06-03 05:59:58', 18.00, 'E9FRA'),
(21, 4, 25, '2023-06-02 06:00:18', '2023-06-03 06:00:18', 13.00, 'TAO0Q'),
(22, 4, 5, '2023-06-02 06:00:35', '2023-06-03 06:00:35', 18.00, 'N7QOB'),
(23, 4, 9, '2023-06-02 06:01:04', '2023-06-03 06:01:04', 13.00, 'W89AK'),
(24, 4, 8, '2023-06-02 06:01:21', '2023-06-03 06:01:21', 14.00, 'V35T6'),
(25, 4, 20, '2023-06-02 06:01:39', '2023-06-03 06:01:39', 15.00, 'JAEAE'),
(26, 4, 20, '2023-06-02 06:01:40', '2023-06-03 06:01:40', 15.00, 'QUJ5H'),
(27, 4, 25, '2023-06-02 06:02:01', '2023-06-03 06:02:01', 13.00, 'U40BT'),
(28, 5, 7, '2023-06-02 06:05:23', '2023-06-03 06:05:23', 12.00, 'BPMSY'),
(29, 5, 6, '2023-06-02 06:05:49', '2023-06-03 06:05:49', 18.00, 'KO0X6'),
(30, 5, 12, '2023-06-02 06:05:57', '2023-06-03 06:05:57', 13.00, 'SGN32'),
(31, 5, 21, '2023-06-02 06:06:05', '2023-06-03 06:06:05', 15.00, 'MRVR0'),
(32, 3, 8, '2023-06-02 07:06:15', '2023-06-03 07:06:15', 12.60, 'U13J3'),
(33, 3, 20, '2023-06-02 07:06:32', '2023-06-03 07:06:32', 13.50, 'PAJKY'),
(34, 8, 2, '2023-06-02 07:12:46', '2023-06-03 07:12:46', 12.00, 'L6445'),
(35, 8, 1, '2023-06-02 07:26:45', '2023-06-03 07:26:45', 22.00, 'Z8QLC'),
(36, 3, 17, '2023-06-02 07:27:06', '2023-06-03 07:27:06', 18.00, '9V6IP'),
(37, 3, 3, '2023-06-02 07:27:47', '2023-06-03 07:27:47', 15.00, '5R54M'),
(38, 1, 24, '2023-06-03 17:05:15', '2023-06-04 17:05:15', 14.00, 'RZQX4'),
(39, 1, 7, '2023-06-03 17:05:43', '2023-06-04 17:05:43', 12.00, 'VIIIF'),
(40, 8, 18, '2023-05-01 19:21:00', '2023-05-02 19:22:00', 15.00, '53AFD'),
(41, 3, 1, '2023-06-04 12:47:59', '2023-06-05 12:47:59', 22.00, 'I8IPU'),
(42, 1, 8, '2023-06-04 14:19:58', '2023-06-05 14:19:58', 14.00, 'E8M4F'),
(43, 1, 7, '2023-06-04 14:20:06', '2023-06-05 14:20:06', 12.00, '31WRT'),
(44, 1, 7, '2023-06-04 14:20:31', '2023-06-05 14:20:31', 12.00, '10HZJ'),
(45, 1, 1, '2023-06-04 14:20:44', '2023-06-05 14:20:44', 22.00, 'K25EZ'),
(46, 1, 2, '2023-06-04 14:21:30', '2023-06-05 14:21:30', 12.00, 'EPJ5E'),
(47, 1, 13, '2023-06-04 14:23:50', '2023-06-05 14:23:50', 16.00, 'K4FGQ'),
(48, 1, 13, '2023-06-04 14:24:23', '2023-06-05 14:24:23', 16.00, 'VE96F'),
(49, 1, 37, '2023-06-04 16:37:59', '2023-06-05 16:37:59', 20.00, 'EO3F7'),
(50, 1, 39, '2023-06-04 16:38:19', '2023-06-05 16:38:19', 16.00, 'E44KD'),
(51, 1, 9, '2023-06-04 16:38:25', '2023-06-05 16:38:25', 13.00, 'WLWHH'),
(52, 1, 4, '2023-06-04 16:41:19', '2023-06-05 16:41:19', 11.00, '0CIQ7'),
(53, 1, 4, '2023-06-04 16:51:04', '2023-06-05 16:51:04', 11.00, 'I48S8'),
(54, 1, 8, '2023-06-04 16:51:44', '2023-06-05 16:51:44', 14.00, 'R142Q'),
(55, 1, 1, '2023-06-04 16:57:26', '2023-06-05 16:57:26', 22.00, 'B1RTI'),
(56, 3, 25, '2023-06-04 17:10:06', '2023-06-05 17:10:06', 13.00, 'FAZBX'),
(57, 1, 17, '2023-06-04 17:18:43', '2023-06-05 17:18:43', 18.00, 'J4WO1'),
(58, 1, 17, '2023-06-04 17:18:57', '2023-06-05 17:18:57', 18.00, '4YGVJ'),
(59, 1, 17, '2023-06-04 17:18:59', '2023-06-05 17:18:59', 18.00, 'RLQXT'),
(60, 1, 17, '2023-06-04 17:19:02', '2023-06-05 17:19:02', 18.00, '6FLQT'),
(61, 5, 14, '2023-06-04 18:43:22', '2023-06-05 18:43:22', 12.00, 'GRCK7'),
(62, 5, 24, '2023-06-04 18:43:28', '2023-06-05 18:43:28', 14.00, 'CIN5T'),
(63, 5, 5, '2023-06-04 18:43:53', '2023-06-05 18:43:53', 18.00, 'FM13G'),
(64, 5, 5, '2023-06-04 18:43:54', '2023-06-05 18:43:54', 18.00, 'IUFBK'),
(65, 5, 5, '2023-06-04 18:43:56', '2023-06-05 18:43:56', 18.00, '7B19G'),
(66, 5, 5, '2023-06-04 18:43:57', '2023-06-05 18:43:57', 18.00, 'LKWIB'),
(67, 5, 5, '2023-06-04 18:43:58', '2023-06-05 18:43:58', 18.00, 'X8Q75'),
(68, 5, 5, '2023-06-04 18:44:00', '2023-06-05 18:44:00', 18.00, 'CCY8A'),
(69, 5, 5, '2023-06-04 18:44:01', '2023-06-05 18:44:01', 18.00, 'D2VE5'),
(70, 1, 2, '2023-06-04 19:46:43', '2023-06-05 19:46:43', 12.00, 'ESPE0'),
(71, 1, 1, '2023-06-04 19:48:11', '2023-06-05 19:48:11', 22.00, 'CW4Y2'),
(72, 1, 25, '2023-06-04 20:02:09', '2023-06-05 20:02:09', 13.00, 'VPJKH'),
(73, 1, 7, '2023-06-04 20:02:20', '2023-06-05 20:02:20', 12.00, 'WEM2Y'),
(74, 1, 14, '2023-06-04 20:02:26', '2023-06-05 20:02:26', 12.00, 'D84XM'),
(75, 1, 19, '2023-06-04 20:02:32', '2023-06-05 20:02:32', 12.00, '9P4XK'),
(76, 1, 4, '2023-06-04 20:02:46', '2023-06-05 20:02:46', 11.00, '8MUDX'),
(77, 1, 4, '2023-06-04 20:02:50', '2023-06-05 20:02:50', 11.00, '4BP6C'),
(78, 1, 4, '2023-06-04 20:02:53', '2023-06-05 20:02:53', 11.00, '8KR35'),
(79, 1, 1, '2023-06-04 22:04:53', '2023-06-05 22:04:53', 22.00, '55DSP'),
(80, 1, 15, '2023-06-04 22:06:25', '2023-06-05 22:06:25', 19.00, 'PCCCI'),
(81, 1, 15, '2023-06-04 20:07:52', '2023-06-05 20:07:52', 19.00, 'B4AR1'),
(82, 1, 15, '2023-06-04 22:08:34', '2023-06-05 22:08:34', 19.00, '35H80'),
(83, 1, 46, '2023-06-04 22:10:33', '2023-06-05 22:10:33', 12.00, 'HZUW0'),
(84, 9, 1, '2023-06-04 22:42:55', '2023-06-05 22:42:55', 22.00, 'T8OXM'),
(85, 10, 15, '2023-06-04 22:47:34', '2023-06-05 22:47:34', 19.00, 'DKDMO'),
(86, 10, 3, '2023-06-04 22:47:48', '2023-06-05 22:47:48', 15.00, '470JF'),
(87, 10, 16, '2023-06-04 22:48:10', '2023-06-05 22:48:10', 17.00, 'WSBMX'),
(88, 10, 16, '2023-06-04 22:48:32', '2023-06-05 22:48:32', 17.00, '3C9TN'),
(89, 10, 16, '2023-06-04 22:48:35', '2023-06-05 22:48:35', 17.00, 'GN09G'),
(90, 10, 16, '2023-06-04 22:48:37', '2023-06-05 22:48:37', 17.00, 'G93EE'),
(91, 10, 1, '2023-06-04 22:49:00', '2023-06-05 22:49:00', 22.00, '2DV2W'),
(92, 10, 8, '2023-06-04 23:08:53', '2023-06-05 23:08:53', 14.00, '0YN7C'),
(93, 11, 17, '2023-06-04 23:16:04', '2023-06-05 23:16:04', 18.00, 'JHBT8'),
(94, 11, 18, '2023-06-04 23:16:26', '2023-06-05 23:16:26', 18.00, 'N6UKR'),
(95, 11, 18, '2023-06-04 23:17:08', '2023-06-05 23:17:08', 18.00, 'IYBFL'),
(96, 11, 48, '2023-06-04 23:17:30', '2023-06-05 23:17:30', 10.00, '2A16J'),
(97, 11, 12, '2023-06-04 23:17:39', '2023-06-05 23:17:39', 13.00, '9CWE2'),
(98, 11, 12, '2023-06-04 23:31:32', '2023-06-05 23:31:32', 13.00, 'AU6XL'),
(99, 11, 47, '2023-06-04 23:32:22', '2023-06-05 23:32:22', 15.00, 'QVP15'),
(100, 11, 48, '2023-06-04 23:32:32', '2023-06-05 23:32:32', 10.00, 'IR4R2'),
(101, 12, 6, '2023-06-04 23:35:13', '2023-06-05 23:35:13', 18.00, '8YD73'),
(102, 12, 9, '2023-06-04 23:42:27', '2023-06-05 23:42:27', 13.00, 'L833Y'),
(103, 12, 16, '2023-06-04 23:42:41', '2023-06-05 23:42:41', 17.00, '7NDRX'),
(104, 12, 46, '2023-06-04 23:42:50', '2023-06-05 23:42:50', 12.00, '3BFH4'),
(105, 12, 16, '2023-06-04 23:45:23', '2023-06-05 23:45:23', 17.00, 'I8JJF'),
(106, 12, 11, '2023-06-04 23:45:40', '2023-06-05 23:45:40', 16.00, 'PRLY4'),
(107, 13, 54, '2023-06-05 09:38:31', '2023-06-06 09:38:31', 14.00, 'MBYCA'),
(108, 1, 15, '2023-06-05 10:32:46', '2023-06-06 10:32:46', 19.00, 'IG2LV'),
(109, 12, 16, '2023-06-05 11:50:52', '2023-06-06 11:50:52', 17.00, 'CX4U1'),
(110, 12, 48, '2023-06-05 11:51:01', '2023-06-06 11:51:01', 10.00, '14HDW'),
(111, 12, 41, '2023-06-05 11:51:31', '2023-06-06 11:51:31', 16.00, '9JI8V'),
(112, 12, 10, '2023-06-05 11:55:19', '2023-06-06 11:55:19', 18.00, 'EKI4N'),
(113, 12, 51, '2023-06-05 11:55:32', '2023-06-06 11:55:32', 15.00, 'P4EGL'),
(114, 12, 51, '2023-06-05 11:55:35', '2023-06-06 11:55:35', 15.00, 'LM9EC'),
(115, 12, 51, '2023-06-05 11:55:38', '2023-06-06 11:55:38', 15.00, 'GOT9K'),
(116, 12, 51, '2023-06-05 11:55:42', '2023-06-06 11:55:42', 15.00, '46PB3'),
(117, 14, 42, '2023-06-05 12:02:05', '2023-06-06 12:02:05', 16.00, 'GJUEV'),
(118, 14, 23, '2023-06-05 12:02:23', '2023-06-06 12:02:23', 19.50, '60C42'),
(119, 14, 1, '2023-06-05 12:02:41', '2023-06-06 12:02:41', 22.00, 'RN3O2'),
(120, 14, 22, '2023-06-05 12:02:51', '2023-06-06 12:02:51', 20.00, 'BITYO'),
(121, 14, 11, '2023-06-05 12:03:08', '2023-06-06 12:03:08', 16.00, '1MJE1'),
(122, 14, 6, '2023-06-05 12:03:29', '2023-06-06 12:03:29', 18.00, 'QZ3W5'),
(123, 14, 11, '2023-06-05 12:13:18', '2023-06-06 12:13:18', 16.00, '1QW74'),
(124, 14, 6, '2023-06-05 12:16:12', '2023-06-06 12:16:12', 18.00, 'QHRQJ'),
(125, 14, 6, '2023-06-05 12:17:07', '2023-06-06 12:17:07', 18.00, '1LNYY'),
(126, 15, 1, '2023-06-05 12:20:32', '2023-06-06 12:20:32', 22.00, 'EVSNI'),
(127, 15, 44, '2023-06-05 12:20:45', '2023-06-06 12:20:45', 17.00, 'PUHTQ'),
(128, 15, 47, '2023-06-05 12:20:59', '2023-06-06 12:20:59', 15.00, 'KRQNB'),
(129, 15, 47, '2023-06-05 12:21:07', '2023-06-06 12:21:07', 15.00, 'LDANL'),
(130, 15, 12, '2023-06-05 12:21:18', '2023-06-06 12:21:18', 13.00, '0KIWB'),
(131, 15, 12, '2023-06-05 12:24:04', '2023-06-06 12:24:04', 13.00, 'AGD5Q'),
(132, 15, 12, '2023-06-05 12:24:15', '2023-06-06 12:24:15', 13.00, '2UI9Q'),
(133, 16, 18, '2023-06-05 12:25:29', '2023-06-06 12:25:29', 18.00, 'QY5U2'),
(134, 16, 46, '2023-06-05 12:25:52', '2023-06-06 12:25:52', 12.00, 'X8SNI'),
(135, 16, 13, '2023-06-05 12:26:10', '2023-06-06 12:26:10', 16.00, 'VSMB5');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `admin_role` tinyint(1) NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `orders_count` int(11) NOT NULL DEFAULT 0,
  `loyalty_card` tinyint(1) NOT NULL DEFAULT 0,
  `id_fav_category` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `admin_role`, `name`, `email`, `password`, `orders_count`, `loyalty_card`, `id_fav_category`) VALUES
(1, 1, 'admin0', 'admin0@example.com', '$2y$10$5HPGQixoQEt3oOxNxGqeGeFoExcT1HEOAC/Nz5KS1L44/rdGHvC9e', 36, 1, 10),
(3, 0, 'adam1', 'adam1@example.com', '$2y$10$WRjBaS1Er5ZwZKc3wqry5O2a9sU0ZwoDc3Awi09S6KkjlQvplNr2e', 16, 1, 1),
(4, 0, 'ewa2', 'ewa2@example.com', '$2y$10$GP7AK6qHXzcCoauHtwdUV.fhLJUKaP4SM0jqfuyJzyIW55Xbb1Gpu', 12, 1, NULL),
(5, 0, 'user1', 'user12345678@gmail.com', '$2y$10$Q06L3FtH3uuW1h6VtqasB.IgU4NLQsGd16e2iKeNhVx7QVJCW2iBu', 13, 1, 6),
(8, 0, 'Natisza', 'szabatnatalia14@gmail.com', '$2y$10$VOCqP6967dDBYy.UXmICq.KOSDtW6Ny6dmrb23ubUQWCm0K4x4n9a', 1, 0, NULL),
(9, 0, 'user2', 'user2@example.com', '$2y$10$gH6AEctHJxHjXKlB/FBZg.V.AsHTHwBWBk0XHtBtBLEihChT/Nk2K', 1, 0, NULL),
(10, 0, 'user3', 'user3@example.com', '$2y$10$3iUl7gz8u4s0W7gQPm/fIu03BNWF35DuNwzhvF.4DfcU9h8EPbsXS', 8, 0, 4),
(11, 0, 'user4', 'user4@e.com', '$2y$10$eljITE0ivdz3Giv5TnpAXuMNdkR6r/nrm3zW5bxSgA2YgKM.pBAca', 8, 0, 4),
(12, 0, 'user5', 'user5@e.com', '$2y$10$Tzu4PeQQ2OGdT4G9Ptv1.Ofxy8s2NItH1FwO3.iL8nRYzTph1L5Iy', 14, 1, 6),
(13, 0, 'karolWwojnar47', 'k2@wp.pl', '$2y$10$r3/SA72cf0ObSblWMoLa/.TB4/NZJ4xlxdeRsPNIlLqiKK2ya/qZi', 1, 0, 9),
(14, 0, 'user6', 'u6@e.com', '$2y$10$YqtzhWjtY3/YpkP8OPNVQ.UIBgVREvpCmwDxYdetuvL8uKl/86xgC', 9, 0, 1),
(15, 0, 'user7', 'u7@e.com', '$2y$10$3uUs0/m3t3kH9p0jwYK/BOQEIjdK5.zbm9Cjxp8HqF/QdovrCi04K', 7, 0, 4),
(16, 0, 'user8', 'u8@e.com', '$2y$10$6o.pcBRWode8kQr2ZmRytuRlnH5804e5xRJo2L2Wh.nAW3P.AVBhC', 3, 0, 9);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `actors`
--
ALTER TABLE `actors`
  ADD PRIMARY KEY (`actor_id`);

--
-- Indeksy dla tabeli `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indeksy dla tabeli `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeksy dla tabeli `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`movie_id`),
  ADD KEY `movies_category_id_foreign` (`category_id`);

--
-- Indeksy dla tabeli `movies_actors_transfer`
--
ALTER TABLE `movies_actors_transfer`
  ADD KEY `movies_actors_transfers_movie_id_foreign` (`movie_id`),
  ADD KEY `movies_actors_transfers_actor_id_foreign` (`actor_id`);

--
-- Indeksy dla tabeli `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_movie_id_foreign` (`movie_id`);

--
-- Indeksy dla tabeli `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeksy dla tabeli `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_id_fav_category_foreign` (`id_fav_category`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `actors`
--
ALTER TABLE `actors`
  MODIFY `actor_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `movie_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `movies_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `movies_actors_transfer`
--
ALTER TABLE `movies_actors_transfer`
  ADD CONSTRAINT `movies_actors_transfers_actor_id_foreign` FOREIGN KEY (`actor_id`) REFERENCES `actors` (`actor_id`),
  ADD CONSTRAINT `movies_actors_transfers_movie_id_foreign` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_movie_id_foreign` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`),
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_id_fav_category_foreign` FOREIGN KEY (`id_fav_category`) REFERENCES `categories` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
