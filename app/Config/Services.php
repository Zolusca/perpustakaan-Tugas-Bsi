<?php

namespace Config;

use App\Exception\DatabaseConnectionFull;
use App\Exception\DatabaseFailedInsert;
use App\Libraries\LoggerCreations;
use CodeIgniter\Config\BaseService;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Database\MySQLi\Connection;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use function PHPUnit\Framework\throwException;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    private static array $databasePool=array();
    private static \Monolog\Logger $logger;

    public function __construct()
    {
        self::$logger=LoggerCreations::LoggerCreations(Services::class);
    }


    /**
     * mendapatkan database connection, Jangan lupa gunakan Service::closeDatabaseConnection()
     * dan membatasi max koneksi 10
     * @return BaseConnection|null
     * @throws DatabaseConnectionFull
     * @throws DatabaseException
     */
    public static function getDatabaseConnection()
    {
        // mengecek jumlah koneksi yang ada
        if (count(self::$databasePool) < 10) {
            try{
                $databaseConnection = db_connect(null, false);

                // cek database apakah terkoneksi atau tidak
                $databaseConnection->initialize();

                // Menyimpan koneksi dengan indeks manual
                $index = count(self::$databasePool);
                self::$databasePool[$index] = $databaseConnection;

                self::$logger->debug("database created array databasePool -> " . count(self::$databasePool));

                // return baseConnection
                return $databaseConnection;

            }
            // catch ketika database tidak dapat terkoneksi dengan program
            catch (DatabaseException $exception){
                self::$logger->error("Database connection got problem, please check database is it alive?");
                self::$logger->error($exception->getMessage());

                throw new DatabaseException("got problem on database");
            }

        } else {
            // langkah ketika connection full
            self::$logger->error("Connection full, max is 10");
            throw new DatabaseConnectionFull(
                "connection full, please wait",
                ResponseInterface::HTTP_SERVICE_UNAVAILABLE
            );
        }
    }

    /**
     * menutup koneksi yang telah digunakan
     * @param BaseConnection $connection
     * @return void
     */
    public static function closeDatabaseConnection(BaseConnection $connection): void
    {
        // Mendapatkan indeks koneksi yang akan dihapus
        $index = array_search($connection, self::$databasePool, true);

        if ($index !== false) {
            // Menutup koneksi
            $connection->close();

            // Menghapus koneksi dari array dengan indeks manual
            unset(self::$databasePool[$index]);

            self::$logger->debug("database pool decrement -1 ,and database connection on databasePool " .count(self::$databasePool));
        }
    }

}
