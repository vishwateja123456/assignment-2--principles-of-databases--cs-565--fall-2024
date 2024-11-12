<?php
require_once 'includes/config.php'; // Here, we are Included the database connection file

// Query 1: Here,we have count total macOS versions released
$totalVersionsQuery = $pdo->query("SELECT COUNT(*) AS total_versions FROM macos_versions");
$totalVersions = $totalVersionsQuery->fetch(PDO::FETCH_ASSOC)['total_versions'];

// Query 2: Here,we have to get all macOS versions with their details
$macosDetailsQuery = $pdo->query("
    SELECT version_name, release_name, darwin_os_number, date_announced, date_released, date_latest_release
    FROM macos_versions
    ORDER BY date_released;
");
$macosDetails = $macosDetailsQuery->fetchAll(PDO::FETCH_ASSOC);

// Query 3: Here,we have to get macOS version name, release name, and year released by date released
$macosYearsQuery = $pdo->query("
    SELECT CONCAT(version_name, ' (', release_name, ')') AS version_release_name,
           YEAR(date_released) AS year_released
    FROM macos_versions
    ORDER BY date_released;
");
$macosYears = $macosYearsQuery->fetchAll(PDO::FETCH_ASSOC);

// Query 4: Here,we have to get the current inventory excluding comments
$inventoryQuery = $pdo->query("
    SELECT model_name, model_identifier, model_number, part_number, serial_number,
           darwin_os_number, latest_supporting_darwin_os_number, support_url
    FROM inventory;
");
$inventory = $inventoryQuery->fetchAll(PDO::FETCH_ASSOC);

// Query 5: Here,we have to show model, installed/original OS, and last supported OS for the current inventory
$installedOSQuery = $pdo->query("
    SELECT inventory.model_name, versions_installed.release_name AS installed_os,
           versions_last_supported.release_name AS last_supported_os
    FROM inventory
    LEFT JOIN macos_versions AS versions_installed ON inventory.darwin_os_number = versions_installed.darwin_os_number
    LEFT JOIN macos_versions AS versions_last_supported ON inventory.latest_supporting_darwin_os_number = versions_last_supported.darwin_os_number;
");
$installedOS = $installedOSQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Apple Macintosh Computer Inventory</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
    <h1>Apple Macintosh Computer Inventory</h1>
  </header>
  <main>

    <!-- Total Versions of macOS Released -->
    <section>
      <h2>How Many Versions of macOS Have Been Released?</h2>
      <div>
        <p>There have been <b><?php echo htmlspecialchars($totalVersions); ?></b> versions of macOS released thus far.</p>
      </div>
    </section>


    <!-- macOS Versions with Detailed Information -->
    <section>
      <h2>Show the Version Name, Release Name, Official Darwin OS Number, Date Announced, Date Released, and Date of Latest Release of All macOS Versions, Listed by Date Order</h2>
      <table>
        <thead>
          <tr>
            <th>Version Name</th>
            <th>Release Name</th>
            <th>Official Darwin OS Number</th>
            <th>Date Announced</th>
            <th>Date Released</th>
            <th>Date of Latest Release</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($macosDetails as $version): ?>
            <tr>
              <td><?php echo htmlspecialchars($version['version_name']); ?></td>
              <td><?php echo htmlspecialchars($version['release_name']); ?></td>
              <td><?php echo htmlspecialchars($version['darwin_os_number']); ?></td>
              <td><?php echo htmlspecialchars($version['date_announced']); ?></td>
              <td><?php echo htmlspecialchars($version['date_released']); ?></td>
              <td><?php echo htmlspecialchars($version['date_latest_release']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>


    <!-- macOS Versions by Year Released -->
    <section>
      <h2>Show the Version Name (Release Name) and Year Released of all macOS Versions, Listed by Date Released</h2>
      <table>
        <thead>
          <tr>
            <th>Version Name (Release Name)</th>
            <th>Year Released</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($macosYears as $year): ?>
            <tr>
              <td><?php echo htmlspecialchars($year['version_release_name']); ?></td>
              <td><?php echo htmlspecialchars($year['year_released']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>


    <!-- Current Inventory Excluding Comments -->
    <section>
      <h2>Show the Current Inventory (Excluding Comments)</h2>
      <table>
        <thead>
          <tr>
            <th>Model Name</th>
            <th>Model Identifier</th>
            <th>Model Number</th>
            <th>Part Number</th>
            <th>Serial Number</th>
            <th>Darwin OS Number</th>
            <th>Latest Supporting Darwin OS Number</th>
            <th>URL</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($inventory as $item): ?>
            <tr>
              <td><?php echo htmlspecialchars($item['model_name']); ?></td>
              <td><?php echo htmlspecialchars($item['model_identifier']); ?></td>
              <td><?php echo htmlspecialchars($item['model_number']); ?></td>
              <td><?php echo htmlspecialchars($item['part_number']); ?></td>
              <td><?php echo htmlspecialchars($item['serial_number']); ?></td>
              <td><?php echo htmlspecialchars($item['darwin_os_number']); ?></td>
              <td><?php echo htmlspecialchars($item['latest_supporting_darwin_os_number']); ?></td>
              <td><a href="<?php echo htmlspecialchars($item['support_url']); ?>" target="_blank">Link</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>


    <!-- Model, Installed/Original OS, and Last Supported OS for Inventory -->
    <section>
      <h2>Show the Model, Installed/Original OS, and the Last Supported OS For the Current Inventory</h2>
      <table>
        <thead>
          <tr>
            <th>Model</th>
            <th>Installed/Original OS</th>
            <th>Last Supported OS</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($installedOS as $os): ?>
            <tr>
              <td><?php echo htmlspecialchars($os['model_name']); ?></td>
              <td><?php echo htmlspecialchars($os['installed_os']); ?></td>
              <td><?php echo htmlspecialchars($os['last_supported_os']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>

  </main>
</body>
</html>
