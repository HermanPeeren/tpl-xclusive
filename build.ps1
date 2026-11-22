# Builds the installable template zip from src/ into dist/.
# Usage (PowerShell 5.1 or 7):  .\build.ps1
# Uses System.IO.Compression directly: Compress-Archive in PowerShell 5.1 writes
# backslashes in entry names, which Joomla on Linux does not unpack correctly.

$ErrorActionPreference = 'Stop'
Add-Type -AssemblyName System.IO.Compression
Add-Type -AssemblyName System.IO.Compression.FileSystem

$root     = $PSScriptRoot
$src      = Join-Path $root 'src'
$manifest = [xml](Get-Content -Raw -Encoding UTF8 (Join-Path $src 'templateDetails.xml'))
$version  = $manifest.extension.version
$dist     = Join-Path $root 'dist'
$zipPath  = Join-Path $dist "tpl_xclusive-$version.zip"

New-Item -ItemType Directory -Force $dist | Out-Null
if (Test-Path $zipPath) { Remove-Item $zipPath }

$zip = [System.IO.Compression.ZipFile]::Open($zipPath, 'Create')
try {
    Get-ChildItem -Path $src -Recurse -File | ForEach-Object {
        $entry = $_.FullName.Substring($src.Length + 1).Replace('\', '/')
        [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile($zip, $_.FullName, $entry, 'Optimal') | Out-Null
    }
} finally {
    $zip.Dispose()
}

Write-Output "Built $zipPath"
