# PowerShell Script to Generate All Admin and Frontend Views
# Usage: .\generate-views.ps1

$sections = @(
    @{
        Name = "news"
        Model = "NewsArticle"
        ModelVar = "news"
        Title = "News Article"
        TitlePlural = "News Articles"
        Fields = @("title", "summary", "content", "featured_image", "category", "type", "status", "is_breaking")
    },
    @{
        Name = "magazine"
        Model = "MagazineIssue"
        ModelVar = "magazine"
        Title = "Magazine Issue"
        TitlePlural = "Magazine Issues"
        Fields = @("title", "issue_number", "description", "content", "cover_image", "pdf_file", "status")
    },
    @{
        Name = "workshops"
        Model = "Workshop"
        ModelVar = "workshop"
        Title = "Workshop"
        TitlePlural = "Workshops"
        Fields = @("title", "description", "overview", "reason", "workshop_date", "location", "status")
    },
    @{
        Name = "symposiums"
        Model = "Symposium"
        ModelVar = "symposium"
        Title = "Symposium"
        TitlePlural = "Symposiums"
        Fields = @("title", "description", "content", "theme", "symposium_date", "location", "status")
    },
    @{
        Name = "guides"
        Model = "PracticalGuide"
        ModelVar = "guide"
        Title = "Practical Guide"
        TitlePlural = "Practical Guides"
        Fields = @("title", "description", "content", "category", "difficulty_level", "status")
    },
    @{
        Name = "meetings"
        Model = "Meeting"
        ModelVar = "meeting"
        Title = "Meeting"
        TitlePlural = "Meetings"
        Fields = @("title", "description", "meeting_date", "start_time", "end_time", "location", "type", "status")
    },
    @{
        Name = "calendar"
        Model = "CalendarEvent"
        ModelVar = "calendar"
        Title = "Calendar Event"
        TitlePlural = "Calendar Events"
        Fields = @("title", "description", "event_date", "event_type", "location")
    }
)

foreach ($section in $sections) {
    Write-Host "Generating views for $($section.Title)..." -ForegroundColor Green
    
    # Create directories
    $adminPath = "resources\views\admin\$($section.Name)"
    New-Item -ItemType Directory -Force -Path $adminPath | Out-Null
    
    # Copy blog templates and replace
    $templates = @("index", "create", "edit")
    
    foreach ($template in $templates) {
        $sourcePath = "resources\views\admin\blog\$template.blade.php"
        $destPath = "$adminPath\$template.blade.php"
        
        if (Test-Path $sourcePath) {
            $content = Get-Content $sourcePath -Raw
            
            # Replace blog references
            $content = $content -replace 'blog', $section.Name
            $content = $content -replace 'Blog Post', $section.Title
            $content = $content -replace 'Blog Posts', $section.TitlePlural
            $content = $content -replace '\$blog', "`$$($section.ModelVar)"
            $content = $content -replace '@section\(''header'', ''.*''\)', "@section('header', '$($section.TitlePlural)')"
            
            # Save
            $content | Out-File -FilePath $destPath -Encoding UTF8
            Write-Host "  Created: $destPath" -ForegroundColor Cyan
        }
    }
}

Write-Host "`nAll views generated successfully!" -ForegroundColor Green
Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "1. Review and customize each view's form fields"
Write-Host "2. Test each section in the admin panel"
Write-Host "3. Create frontend views for public display"
