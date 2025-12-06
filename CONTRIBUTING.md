# Contributing to Telemedicine Management System

Thank you for your interest in contributing to the Telemedicine Management System! This document provides guidelines and instructions for contributing.

## Code of Conduct

- Be respectful and professional
- Welcome all contributions
- Focus on improving the project
- Report issues constructively

## How to Contribute

### 1. Fork the Repository
```bash
# Click "Fork" on GitHub to create your own copy
```

### 2. Clone Your Fork
```bash
git clone https://github.com/yourusername/telemedicine.git
cd telemedicine
```

### 3. Create a Feature Branch
```bash
git checkout -b feature/your-feature-name
# or for bug fixes
git checkout -b fix/bug-description
```

### 4. Make Your Changes
- Write clean, readable code
- Follow PHP PSR-12 coding standards
- Add comments for complex logic
- Test your changes locally

### 5. Commit Your Changes
```bash
git add .
git commit -m "Brief description of changes

- Detailed point 1
- Detailed point 2
- References issue #123"
```

### 6. Push to Your Fork
```bash
git push origin feature/your-feature-name
```

### 7. Create a Pull Request
- Go to GitHub and create a Pull Request
- Provide a clear description of changes
- Reference any related issues
- Wait for review and feedback

## Development Guidelines

### PHP Code Standards
- Use PSR-12 coding standards
- Properly indent with 4 spaces
- Use meaningful variable and function names
- Add PHPDoc comments for functions

Example:
```php
/**
 * Authenticate user with credentials
 *
 * @param string $username User email or username
 * @param string $password User password
 * @return array Authentication result
 */
public function authenticate($username, $password) {
    // Implementation
}
```

### Database Changes
- Create migration files for schema changes
- Include rollback functionality
- Test thoroughly before committing
- Document database changes

### API Changes
- Update API endpoint documentation
- Maintain backward compatibility when possible
- Include request/response examples
- Test with different HTTP clients

### Frontend Changes
- Ensure responsive design
- Test on multiple browsers
- Maintain CSS organization
- Use semantic HTML

## Testing

### Local Testing
1. Set up XAMPP locally
2. Import database.sql
3. Test all features manually
4. Check browser console for errors
5. Verify API responses

### Testing Checklist
- [ ] All authentication flows work
- [ ] All user roles can access their dashboards
- [ ] API endpoints return correct data
- [ ] Forms validate input properly
- [ ] Database operations complete successfully
- [ ] No JavaScript errors in console
- [ ] Responsive design works on mobile

## Reporting Issues

When reporting bugs, please include:
1. **Description**: Clear explanation of the issue
2. **Steps to Reproduce**: How to recreate the problem
3. **Expected Behavior**: What should happen
4. **Actual Behavior**: What actually happened
5. **Environment**: PHP version, Browser, OS
6. **Screenshots**: If applicable

### Issue Template
```
**Description**
[Explain the issue clearly]

**Steps to Reproduce**
1. [First step]
2. [Second step]
3. [What happens]

**Expected Behavior**
[What should happen]

**Actual Behavior**
[What actually happens]

**Environment**
- PHP Version: 
- Browser: 
- OS: 

**Screenshots**
[If applicable]
```

## Documentation

### When to Update Docs
- Adding new features
- Changing API endpoints
- Modifying database schema
- Adding new user roles
- Updating dependencies

### Documentation Files
- **README.md**: Main project overview
- **docs/SETUP_GUIDE.md**: Installation instructions
- **docs/API_REFERENCE.md**: API documentation
- **docs/ADMIN_GUIDE.md**: Admin features
- **docs/CASHIER_GUIDE.md**: Cashier features
- **docs/HEALTHCARE_PROVIDER_GUIDE.md**: Provider features
- **docs/PHARMACIST_GUIDE.md**: Pharmacist features

## Commit Message Guidelines

Use clear, descriptive commit messages:

```
# Good
git commit -m "Add patient search functionality"
git commit -m "Fix login validation error"
git commit -m "Update API documentation"

# Avoid
git commit -m "fix"
git commit -m "update"
git commit -m "changes"
```

### Commit Message Format
```
[Type] Brief description (50 chars max)

Detailed explanation of what and why (if needed)

- Related changes
- Implementation details
- References #issue-number
```

**Types**:
- `feat:` New feature
- `fix:` Bug fix
- `docs:` Documentation
- `style:` Code style (formatting)
- `refactor:` Code refactoring
- `test:` Test additions
- `chore:` Build/dependency updates

## Pull Request Process

1. **Before Submitting**
   - Update documentation
   - Add tests if applicable
   - Review your own changes first
   - Ensure no merge conflicts

2. **PR Title Format**
   ```
   [Type] Brief description
   ```

3. **PR Description**
   ```markdown
   ## Description
   Brief explanation of changes
   
   ## Type of Change
   - [ ] Bug fix
   - [ ] New feature
   - [ ] Documentation update
   
   ## Related Issues
   Closes #123
   
   ## Testing Done
   - Tested with user role X
   - Verified API response
   - Checked responsive design
   
   ## Screenshots
   [If applicable]
   ```

4. **Review Process**
   - Maintainers will review your code
   - Address feedback and suggestions
   - Make requested changes
   - Get approval before merge

## Performance Considerations

- Optimize database queries
- Use prepared statements
- Cache data when appropriate
- Minimize API calls
- Compress images and assets

## Security Considerations

- Always use prepared statements for database queries
- Validate and sanitize user input
- Use password hashing for credentials
- Implement proper access control
- Never commit sensitive information
- Use environment variables for secrets

## Setting Up Development Environment

### Windows (XAMPP)
```bash
# 1. Install XAMPP
# 2. Clone the repo to htdocs
git clone https://github.com/yourusername/telemedicine.git C:\xampp\htdocs\telemedicine

# 3. Start Apache and MySQL in XAMPP Control Panel

# 4. Import database
# Go to http://localhost/phpmyadmin
# Import database.sql

# 5. Access system
# Go to http://localhost/telemedicine
```

### Linux/Mac (XAMPP Alternative)
```bash
# Using local PHP and MySQL
php -S localhost:8000
# or use Docker, MAMP, or other solutions
```

## Questions?

- Check existing issues and documentation
- Ask in pull request comments
- Email: stevemzemba009@gmail.com

## Recognition

Contributors will be recognized in:
- Project README
- GitHub contributors page
- Release notes

---

**Thank you for contributing to make this project better!**
