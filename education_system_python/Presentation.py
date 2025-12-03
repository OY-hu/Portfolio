import json
import random
import datetime
from datetime import datetime, timedelta

# Number to generate random OTP
OTP_CHARS = "0123456789"

# Read all JSON files for login system
try:
    with open("student.json", "r") as student_file:
        student_data = json.load(student_file)
except FileNotFoundError:
    student_data = []

try:
    with open("teacher.json", "r") as teacher_file:
        teacher_data = json.load(teacher_file)
except FileNotFoundError:
    teacher_data = []

try:
    with open("staff.json", "r") as staff_file:
        staff_data = json.load(staff_file)
except FileNotFoundError:
    staff_data = []

try:
    with open("administrator.json", "r") as administrator_file:
        administrator_data = json.load(administrator_file)
except FileNotFoundError:
    administrator_data = []

try:
    with open("results.json", "r") as results_file:
        student_results = json.load(results_file)
except FileNotFoundError:
    student_results = []

try:
    with open("messages.json", "r") as messages_file:
        messages_data = json.load(messages_file)
except FileNotFoundError:
    messages_data = []

try:
    with open("course.json", 'r') as course_file:
        course_data = json.load(course_file)
except FileNotFoundError:
    course_data = []

try:
    with open("timetable.json", "r") as timetable_file:
        timetables = json.load(timetable_file)
except FileNotFoundError:
    timetables = {}

try:
    with open("config.json", "r") as config_file:
        config_data = json.load(config_file)
except FileNotFoundError:
    config_data = {"days": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
                   "subjects": ["Math", "Science", "History"],
                   "timeslots": ["09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00"]}

try:
    with open("events.json", "r") as events_file:
        events_data = json.load(events_file)
except FileNotFoundError:
    events_data = {}

try:
    with open("course.json", "r") as course_file:
        course_list = json.load(course_file)
except FileNotFoundError:
    course_list = []

try:
    with open("assignment.json", "r") as assignment_file:
        assignment_data = json.load(assignment_file)
except FileNotFoundError:
    assignment_data = {}

try:
    with open("announcement.json", "r") as announcement_file:
        announcements_data = json.load(announcement_file)
except FileNotFoundError:
    announcements_data = []

try:
    with open("exam.json", 'r') as f:
        exam_data = json.load(f)  # Define exam_data globally
    print("Exam schedules loaded successfully!")
except FileNotFoundError:
    print("No exam schedule file found, initializing empty exam data.")
    exam_data = {}


# Function to load student data
def load_student_data():
    try:
        with open("student.json", "r") as file:
            return json.load(file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("⚠ No student records found! Starting with empty list.")
        return []


# Function to generate student ID during creating account
def generate_student_id():
    return f"TP0{random.randint(1000, 9999)}"


# Function to generate an ID for events
def generate_event_id():
    return f"E{random.randint(0, 999)}"


# Function to generate OTP for attendance
def generate_otp():
    return ''.join(random.choices(OTP_CHARS, k=3))


# Function to add new students
def add_student(student_data):
    # Define available courses
    available_courses = [
        "Software Engineering",
        "Computer Science",
        "Data Science"
    ]

    # Display courses with numbers
    print("Available courses")
    print("-----------------------")
    for i, course in enumerate(available_courses, 1):
        print(f"{i}. {course}")
    print("-----------------------")

    # Get course selection
    while True:
        try:
            course_choice = int(input("Please enter the number of your course: "))
            if 1 <= course_choice <= len(available_courses):
                selected_course = available_courses[course_choice - 1]
                break
            else:
                print(f"Please enter a number between 1 and {len(available_courses)}")
        except ValueError:
            print("Invalid input. Please enter a number.")

    new_student = {
        "Student ID": generate_student_id(),
        "name": input("Please type in your full name: ").title(),
        "age": input("Please type in your age: "),
        "ethnicity": input("Please type in your ethnicity: "),
        "email": input("Please type in your email: "),
        "course": selected_course,
        "password": input("Please type in your password: "),
        "IC/Passport": input("Please type in your IC/Passport Number: "),
        "Phone Number": input("Please type in your Personal Phone Number: "),
        "Emergency Contact": input("Please type in your Emergency Contact Number: "),
        "Emergency Contact Info": input("Please type in your Emergency Contact Info (Dad/Mom/Guardian): "),
        "Emergency Contact Name": input("Please type in your Emergency Contact Name: "),
        "status": "active"
    }

    student_data.append(new_student)
    with open("student.json", "w") as file:
        json.dump(student_data, file, indent=4)
    print(f"✅ Student account created successfully! Your Student ID: {new_student['Student ID']}")
    return student_data


# Function for teachers, staffs and admins to view and search for students credentials
def view_student(student_id):
    """Lists details of a single student by ID."""
    try:
        with open("student.json", "r") as file:
            create_student_data = json.load(file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("⚠ No student records found!")
        return

    for student in create_student_data:
        if student["Student ID"] == student_id:
            print("-" * 40)
            print(f"🔹 Student ID: {student.get('Student ID', 'N/A')}")
            print(f"   Name: {student.get('name', 'N/A')}")
            print(f"   Age: {student.get('age', 'N/A')}")
            print(f"   Email: {student.get('email', 'N/A')}")
            print(f"   Course: {student.get('course', 'N/A')}")
            print(f"   IC/Passport Number: {student.get('IC/Passport', 'N/A')}")
            print(f"   Personal Phone Number: {student.get('Phone Number', 'N/A')}")
            print(f"   Emergency Contact Number: {student.get('Emergency Contact', 'N/A')}")
            print(f"   Emergency Contact Info: {student.get('Emergency Contact Info', 'N/A')}")
            print(f"   Emergency Contact Name: {student.get('Emergency Contact Name', 'N/A')}")
            print(f"   Status: {student.get('status', 'N/A')}")
            print(f"   Suspend Reason: {student.get('suspend reason', 'N/A')}")
            print("-" * 40)
            return
    print("❌ Student ID not found!")


def view_student_contact(student_id):
    """Lists details of a single student by ID."""
    try:
        with open("student.json", "r") as file:
            create_student_data = json.load(file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("⚠ No student records found!")
        return

    for student in create_student_data:
        if student["Student ID"] == student_id:
            print("-" * 40)
            print(f"🔹 Student ID: {student.get('Student ID', 'N/A')}")
            print(f"   Name: {student.get('name', 'N/A')}")
            print(f"   Email: {student.get('email', 'N/A')}")
            print(f"   Personal Phone Number: {student.get('Phone Number', 'N/A')}")
            print(f"   Emergency Contact Number: {student.get('Emergency Contact', 'N/A')}")
            print(f"   Emergency Contact Name: {student.get('Emergency Contact Name', 'N/A')}")
            print(f"   Emergency Contact Info: {student.get('Emergency Contact Info', 'N/A')}")
            print("-" * 40)
            return
    print("❌ Student ID not found!")


# Function to edit student info
def edit_student_info():
    edit_student_id = input("Enter the student ID: ")
    for student in student_data:
        if student["Student ID"] == edit_student_id:
            print(f"Editing information for {student['name']}")
            student["email"] = input("Enter new email: ") or student["email"]
            student["course"] = input("Enter new course: ") or student["course"]
            student["password"] = input("Enter new password: ") or student["password"]
            student["IC/Passport"] = input("Enter new IC/Passport Number: ") or student["IC/Passport"]
            student["Phone Number"] = input("Enter new Personal Phone Number: ") or student["Phone Number"]
            student["Emergency Contact"] = input("Enter new Emergency Contact Number: ") or student["Emergency Contact"]
            student["Emergency Contact Name"] = input("Enter new Emergency Contact Name): ") or student[
                "Emergency Contact Name"]
            student["Emergency Contact Info"] = input("Enter new Emergency Contact Info (Dad/Mom/Guardian: ") or \
                                                student["Emergency Contact Info"]
            with open("student.json", "w") as file:
                json.dump(student_data, file, indent=4)
            print("Information updated successfully!")
            return
    print("Student ID not found!")


# Function to delete a student from the system
def delete_student(student_id, filename="student.json"):
    """Deletes a student by ID from the JSON file."""
    try:
        # Step 1: Load existing student data
        with open(filename, "r") as file:
            try:
                students = json.load(file)  # Load JSON data
            except json.JSONDecodeError:
                print("Error: JSON file is not formatted correctly.")
                return False
    except FileNotFoundError:
        print("Error: The file does not exist.")
        return False

    # Step 3: Find and remove the student
    updated_students = [student for student in students if student.get("Student ID") != student_id]

    # Step 4: Check if deletion happened
    if len(updated_students) == len(students):
        print(f"❌ No student found with ID {student_id}")
        return False

    # Step 5: Save updated list back to the file
    with open(filename, "w") as file:
        json.dump(updated_students, file, indent=4)

    print(f"✅ Student with ID {student_id} has been deleted successfully.")
    return True


# Function to list all students
def list_all_students(filename="student.json"):
    """Lists all students from the JSON file."""
    try:
        # Load student data
        with open(filename, "r") as file:
            try:
                students = json.load(file)
            except json.JSONDecodeError:
                print("Error: JSON file is not formatted correctly.")
                return False
    except FileNotFoundError:
        print("Error: The file does not exist.")
        return False

    # Check if there are students
    if not students:
        print("⚠ No student records found!")
        return False

    # Display student information
    print("\n📌 List of All Students:\n")
    for student in students:
        print(f"🔹 Student ID: {student.get('Student ID', 'N/A')}")
        print(f"   Name: {student.get('name', 'N/A')}")
        print(f"   Age: {student.get('age', 'N/A')}")
        print(f"   Email: {student.get('email', 'N/A')}")
        print(f"   Course: {student.get('course', 'N/A')}")
        print(f"   IC/Passport Number: {student.get('IC/Passport', 'N/A')}")
        print(f"   Personal Phone Number: {student.get('Phone Number', 'N/A')}")
        print(f"   Emergency Contact Number: {student.get('Emergency Contact', 'N/A')}")
        print(f"   Emergency Contact Name: {student.get('Emergency Contact Name', 'N/A')}")
        print(f"   Emergency Contact Info: {student.get('Emergency Contact Info', 'N/A')}")
        print(f"   Status: {student.get('status', 'N/A')}")
        print(f"   Suspend Reason: {student.get('suspend reason', 'N/A')}")
        print("-" * 40)

    return True


def student_status_update(student_id):
    """
    Toggles a student's status between active and withdrawn.
    If active, withdraws the student. If withdrawn, re-enrolls them.
    """
    # Predefined list of reasons for withdrawal
    reasons = [
        "Academic reasons",
        "Financial reasons",
        "Personal reasons",
        "Health reasons",
        "Transferred to another institution",
        "Other"
    ]

    try:
        # Load student data
        with open("student.json", "r") as file:
            students = json.load(file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("⚠ No student records found!")
        return

    # Find the student by ID
    for student in students:
        if student["Student ID"] == student_id:
            if student["status"] == "Suspend":
                # Re-enroll the student
                if input(f'Re-enroll {student["name"]} (ID: {student_id})? (yes/no): ').lower() == 'yes':
                    student["status"] = "Active"
                    student["suspend reason"] = None
                    print(f"✅ {student['name']} (ID: {student_id}) re-enrolled!")
                else:
                    print("❌ Re-enrollment cancelled.")
            else:
                # Withdraw the student
                if input(f'Suspend {student["name"]} (ID: {student_id})? (yes/no): ').lower() == 'yes':
                    print("Select a reason:")
                    # Display numbered options without enumerate
                    for i in range(len(reasons)):
                        print(f"{i + 1}. {reasons[i]}")
                    choice = input("Enter your choice (1-6): ")
                    if choice in ["1", "2", "3", "4", "5", "6"]:
                        reason = reasons[int(choice) - 1]
                        if reason == "Other":
                            reason = f"Other: {input('Specify the reason: ')}"
                        student["status"] = "Suspend"
                        student["suspend reason"] = reason
                        print(f"✅ {student['name']} (ID: {student_id}) has Suspend! Reason: {reason}")
                    else:
                        print("❌ Invalid choice. Suspend cancelled.")
                else:
                    print("❌ Suspend cancelled.")

            # Save updated data
            with open("student.json", "w") as file:
                json.dump(students, file, indent=4)
            return

    print("❌ Student ID not found!")


# Actions on student
def student_record():
    while True:
        print("\nStudent Record Management System")
        print("1. Add Student")
        print("2. View Student")
        print("3. View Student (Contact)")
        print("4. Update Student")
        print("5. Suspend Student")
        print("6. Delete Student")
        print("7. List All Students")
        print("8. Exit")

        choice = input("Enter your choice: ")

        if choice == '1':
            add_student(student_data)

        elif choice == '2':
            student_id = input("Enter Student ID: ")
            view_student(student_id)

        elif choice == '3':
            student_id = input("Enter Student ID: ")
            view_student_contact(student_id)

        elif choice == '4':
            edit_student_info()

        elif choice == '5':
            student_id = input("Enter Student ID: ")
            student_status_update(student_id)

        elif choice == '6':
            student_id = input("Enter Student ID: ")
            delete_student(student_id)

        elif choice == '7':
            list_all_students()

        elif action == 8:
            print("Logging out...")
            break  # Exit to the main menu
        else:
            print("Invalid action. Please choose between 1 and 6.")
            break  # Exit login loop


# Function to add new staff
def add_staff():
    new_staff = {
        "name": input("Please type in your full name: ").capitalize(),
        "position": input("Please type in your department: ").capitalize(),
        "email": input("Please type in your email: "),
        "password": input("Please type in your password: ")
    }
    staff_data.append(new_staff)
    with open("staff.json", "w") as file:
        json.dump(staff_data, file, indent=4)
        print("✅ Staff account created successfully!")


# Function to add new administrator
def add_administrator():
    new_admin = {
        "name": input("Please type in your full name: ").capitalize(),
        "department": input("Please type in your department: ").capitalize(),
        "email": input("Please type in your email: "),
        "password": input("Please type in your password: ")
    }
    administrator_data.append(new_admin)
    with open("administrator.json", "w") as file:
        json.dump(administrator_data, file, indent=4)
        print("✅ Administrator account created successfully!")
        print("Please re-run the program to log in.")
        quit()


# Function to add new teachers
def add_teacher():
    new_teacher = {
        "name": input("Please type in your full name: ").capitalize(),
        "department": input("Please type in your department: ").capitalize(),  # Fixed typo
        "email": input("Please type in your email: "),
        "password": input("Please type in your password: ")
    }
    teacher_data.append(new_teacher)
    with open("teacher.json", "w") as file:
        json.dump(teacher_data, file, indent=4)
        print("✅ Teacher account created successfully!")


# Function to edit student information
def edit_information(student_id):
    # Search for the student
    for student_record in student_data:
        if student_record["Student ID"] == student_id:
            print(f"Editing information for {student_record['name']}")
            student_record["email"] = input("Enter new email (press Enter to keep current): ") or student_record[
                "email"]
            student_record["password"] = input("Enter new password (press Enter to keep current): ") or student_record[
                "password"]
            # Save updated data back to file
            try:
                with open("student.json", "w") as file:
                    json.dump(student_data, file, indent=4)
                print("Information updated successfully!")
            except IOError as e:
                print(f"Error saving updated information: {e}")
            return
    print("Student ID not found!")


# Function to add assignments
def add_assignment():
    print("Available courses: \nSoftware Engineering \nComputer Science \nData Science")
    add_assignment_course = input("Assignment Course: ").strip()
    add_assignment_subject = input("Assignment Subject: ").strip()
    add_assignment_due_date = input("Due Date: ").strip()
    add_assignment_details = input("Assignment Details: ").strip()

    if add_assignment_course not in assignment_data:
        assignment_data[add_assignment_course] = {}  # Create empty dictionary for course

    if add_assignment_subject not in assignment_data[add_assignment_course]:
        assignment_data[add_assignment_course][add_assignment_subject] = []  # Create empty list for subject

    # Add the assignment as a dictionary with detail and due_date
    assignment_data[add_assignment_course][add_assignment_subject].append({
        "detail": add_assignment_details,
        "due_date": add_assignment_due_date
    })

    try:
        with open("assignment.json", "w") as add_assignment_file:
            json.dump(assignment_data, add_assignment_file, indent=4)
        print(f"Assignment added successfully for {add_assignment_subject} in {add_assignment_course}.")
    except IOError as e:
        print(f"Error saving assignment data: {e}")


# Function to edit assignment
def edit_assignment():
    # Print available courses (top-level keys of assignment_data)
    print(f'Available courses: \n{list(assignment_data.keys())}')
    edit_course = input("Enter the course to edit assignment: ").strip()
    if edit_course not in assignment_data:
        print(f"Course '{edit_course}' not found.")
        return

    # Corrected: Use edit_course instead of add_assignment_course
    print(f'Available subjects: \n{list(assignment_data[edit_course].keys())}')
    edit_subject = input("Enter the subject to edit assignment: ").strip()
    if edit_subject not in assignment_data[edit_course]:
        print(f"Subject '{edit_subject}' not found in course '{edit_course}'.")
        return

    print("Current Assignments:")
    assignments = assignment_data[edit_course][edit_subject]  # List of assignment dictionaries
    for i, assignment in enumerate(assignments, 1):  # Use enumerate with start=1 for numbering
        print(f"{i}. Detail: {assignment['detail']}, Due Date: {assignment['due_date']}")

    try:
        assignment_index = int(input("Enter the assignment number to edit: ")) - 1
        if 0 <= assignment_index < len(assignments):
            new_details = input("Enter the new assignment details: ").strip()
            new_due_date = input("Enter the new Due Date: ").strip()
            assignments[assignment_index] = {
                "detail": new_details,
                "due_date": new_due_date
            }

            try:
                with open("assignment.json", "w") as edit_assignment_file:
                    json.dump(assignment_data, edit_assignment_file, indent=4)
                print(f"Assignment edited successfully for {edit_subject} in {edit_course}.")
            except IOError as e:
                print(f"Error saving assignment data: {e}")

        else:
            print("Invalid assignment number.")
    except ValueError:
        print("Invalid input. Please enter a number.")


# Function to delete assignments
def remove_assignment():
    print(f'Available courses: \n{list(assignment_data.keys())}')
    # Prompt for course selection
    delete_course = input("Enter the course to delete an assignment: ").strip()
    if delete_course not in assignment_data:
        print(f"Course '{delete_course}' not found.")
        return

    # Prompt for subject selection
    print(f'Available subjects: \n{list(assignment_data[delete_course].keys())}')
    delete_subject = input("Enter the subject to delete an assignment: ").strip()
    if delete_subject not in assignment_data[delete_course]:
        print(f"Subject '{delete_subject}' not found in course '{delete_course}'.")
        return

    # Display current assignments for the selected subject
    print("Current Assignments:")
    assignments = assignment_data[delete_course][delete_subject]  # List of assignment dictionaries
    for i, assignment in enumerate(assignments, 1):  # Use enumerate with start=1 for numbering
        print(f"{i}. Detail: {assignment['detail']}, Due Date: {assignment['due_date']}")

    try:
        # Prompt for assignment to delete
        assignment_index = int(input("Enter the assignment number to delete: ")) - 1
        if 0 <= assignment_index < len(assignments):
            # Remove the selected assignment
            deleted_assignment = assignments.pop(assignment_index)
            print(
                f"Assignment '{deleted_assignment['detail']}' with due date '{deleted_assignment['due_date']}' deleted successfully.")

            # If no assignments remain for the subject, optionally remove the subject
            if not assignments:
                del assignment_data[delete_course][delete_subject]
                # If no subjects remain for the course, optionally remove the course
                if not assignment_data[delete_course]:
                    del assignment_data[delete_course]

            # Save the updated data to the JSON file
            try:
                with open("assignment.json", "w") as delete_assignment_file:
                    json.dump(assignment_data, delete_assignment_file, indent=4)
                print(f"Assignment data saved successfully for {delete_subject} in {delete_course}.")
            except IOError as e:
                print(f"Error saving assignment data: {e}")
        else:
            print("Invalid assignment number.")
    except ValueError:
        print("Invalid input. Please enter a number.")


# Function to view all assignments
def view_assignments_by_course():
    # Check if assignment_data is empty or not loaded
    if not assignment_data:
        print("❌ No assignments available.")
        return

    # Display available courses with numbers
    print("\n📋 Available Courses:")
    course_options = {str(i + 1): course for i, course in enumerate(assignment_data.keys())}
    for num, course in course_options.items():
        print(f"  {num}. {course}")

    # Prompt for course selection by number
    course_selection = input("📌 Enter the course number to view assignments: ").strip()
    if course_selection not in course_options:
        print("⚠ Invalid course selection!")
        return

    selected_course = course_options[course_selection]
    # Display assignments for the selected course
    print(f"\n📋 Assignments for {selected_course}:\n")
    course_assignments = assignment_data[selected_course]
    for subject, assignments in course_assignments.items():
        print(f"  📚 Subject: {subject}")
        print("    📝 Assignments:")
        for assignment in assignments:  # Each assignment is a dictionary with 'detail' and 'due_date'
            print(f"      - Detail: {assignment['detail']}")
            print(f"        ⏰ Due Date: {assignment['due_date']}")
        print("\n  " + "-" * 30)
    print("📋 Assignment display complete.")


# Function for students to view their assignments
def load_course_assignments(course_name):
    if course_name not in assignment_data:
        print(f"No assignments found for course '{course_name}'.")
        return
    print("-" * 50)
    print(f"Assignments for {course_name}:")
    course_assignments = assignment_data[course_name]
    for subject, assignments in course_assignments.items():
        print(f"  Subject: {subject}")
        print("    Assignments:")
        for assignment in assignments:  # Each assignment is a dictionary with 'detail' and 'due_date'
            print(f"      - Detail: {assignment['detail']}")
            print(f"        Due Date: {assignment['due_date']}")
            print("-" * 50)


# Function for students to submit assignments given
def submit_assignment(student_id):
    # Load student data to get enrolled course
    try:
        with open("student.json", "r") as file:
            student_data = json.load(file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("Student data not found or corrupted.")
        return

    # Find the student's course
    student_course = None
    for student in student_data:
        if student["Student ID"] == student_id:
            student_course = student["course"]
            break
    if not student_course:
        print(f"Student ID '{student_id}' not found.")
        return

    # Load assignment data
    try:
        with open("assignment.json", "r") as file:
            assignment_data = json.load(file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("No assignments available to submit.")
        return

    # Check if the student's course has assignments
    if student_course not in assignment_data:
        print(f"No assignments available for your course: {student_course}.")
        return

    # Load existing submissions
    try:
        with open("submission.json", "r") as file:
            submission_data = json.load(file)
    except (FileNotFoundError, json.JSONDecodeError):
        submission_data = {}

    # Show available subjects for the student's course with numbers
    subjects = list(assignment_data[student_course].keys())
    print(f"Available subjects in {student_course}:")
    for i, subject in enumerate(subjects, 1):
        print(f"{i}. {subject}")

    # Get subject selection by number
    try:
        subject_index = int(input("Enter the subject number for submission: ")) - 1
        if 0 <= subject_index < len(subjects):
            subject = subjects[subject_index]
        else:
            print("Invalid subject number.")
            return
    except ValueError:
        print("Invalid input. Please enter a number.")
        return

    # Show available assignments for the subject
    print("-" * 50)
    print("Available Assignments:")
    assignments = assignment_data[student_course][subject]
    for i, assignment in enumerate(assignments, 1):
        print(f"{i}. Detail: {assignment['detail']}, \n   Due Date: {assignment['due_date']}")
        print("-" * 50)

    try:
        assignment_index = int(input("Enter the assignment number to submit for: ")) - 1
        if 0 <= assignment_index < len(assignments):
            content = input("Enter your submission content: ").strip()

            # Initialize student entry if not exists
            if student_id not in submission_data:
                submission_data[student_id] = {}

            # Initialize course and subject nested structure
            if student_course not in submission_data[student_id]:
                submission_data[student_id][student_course] = {}
            if subject not in submission_data[student_id][student_course]:
                submission_data[student_id][student_course][subject] = []

            # Add submission
            submission_data[student_id][student_course][subject].append({
                "assignment_detail": assignments[assignment_index]["detail"],
                "submission_content": content,
                "submitted_at": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
                "due_date": assignments[assignment_index]["due_date"]
            })

            # Save to submission.json
            try:
                with open("submission.json", "w") as submission_file:
                    json.dump(submission_data, submission_file, indent=4)
                print(f"Submission for '{subject}' in '{student_course}' by {student_id} saved successfully.")
            except IOError as e:
                print(f"Error saving submission: {e}")
        else:
            print("Invalid assignment number.")
    except ValueError:
        print("Invalid input. Please enter a number.")


# Updated function for teachers to view submissions
def view_submissions():
    try:
        with open("submission.json", "r") as file:
            submission_data = json.load(file)

        if not submission_data:
            print("No submissions available.")
            return

        print("=== Student Assignment Submissions ===")
        for student_id, courses in submission_data.items():
            print(f"\nStudent ID: {student_id}")
            for course, subjects in courses.items():
                print(f"  Course: {course}")
                for subject, submissions in subjects.items():
                    print(f"    Subject: {subject}")
                    print(f"    Total Submissions: {len(submissions)}")
                    for i, submission in enumerate(submissions, 1):
                        print(f"      Submission {i}:")
                        print(f"        Assignment: {submission['assignment_detail']}")
                        print(f"        Content: {submission['submission_content']}")
                        print(f"        Submitted: {submission['submitted_at']}")
                        print(f"        Due Date: {submission['due_date']}")
                    print("    ---")
            print("  ---")
        print("===")
    except FileNotFoundError:
        print("No submissions have been made yet.")
    except json.JSONDecodeError:
        print("Error: Corrupted submission file. Contact the administrator.")
    except Exception as e:
        print(f"Error viewing submissions: {e}")


# Combinations for add_assignment, load_course_assignments,
def manage_course():
    manage_course_action = int(input("1. Manage Assignments \n2. Manage exam \n3. Exit \nChoose An Action: "))

    if manage_course_action == 1:
        manage_assignment_action = int(
            input("1. Add Assignments \n2. Edit Assignments \n3. Delete Assignments \n4. Exit \nChoose An Action: "))
        if manage_assignment_action == 1:
            add_assignment()

        elif manage_assignment_action == 2:
            edit_assignment()


# Function to load timetable for admin teacher and staff
def view_all_timetables(timetables):
    """Display timetable for a user-selected course."""
    if not timetables:
        print("No timetables available!")
        return

    print("\nWhich course timetable would you like to view?")
    print("1. Software Engineering")
    print("2. Computer Science")
    print("3. Data Science")
    course_choice = input("Enter the course number (1, 2, or 3): ")
    course_map = {"1": "Software Engineering", "2": "Computer Science", "3": "Data Science"}

    if course_choice not in course_map:
        print("Invalid course selection!")
        return

    course = course_map[course_choice]
    if course not in timetables:
        print(f"No timetable found for {course}!")
        return

    print(f"\n=== Timetable for {course} ===")
    for day, details in timetables[course].items():
        print("  " + "-" * 50)
        print(f"  Day: {day} - Date: {details.get('date', 'No Date')}")
        for entry in details.get("schedule", []):
            print(f"    - {entry['time']}: {entry['subject']} (Room: {entry['classroom']})")
    print("  " + "-" * 50)
    print("\nTimetable display complete.")


# Function to load timetable by course
def load_timetable_by_course(student_course):
    if not student_course:
        print("❌ Error: No course provided for the student.")
        return
    try:
        with open("timetable.json", "r") as file:
            timetable_records = json.load(file)
        print(f"📌 Available courses in timetable: {list(timetable_records.keys())}")
        print(f"🔍 Debug: Looking for timetable of {student_course}")
        if student_course in timetable_records:
            print(f"\n📅 Timetable for {student_course}:\n")
            course_timetable = timetable_records[student_course]
            for day, details in course_timetable.items():
                print(f"📆 {day} - {details.get('date', 'No Date')}")
                for schedule in details.get("schedule", []):
                    print(f"  ⏰ {schedule['time']}: {schedule['subject']}: {schedule['classroom']}")
                print("\n" + "-" * 30)
        else:
            print(f"⚠ No timetable found for the course: {student_course}")
    except FileNotFoundError:
        print("❌ Error: Timetable file not found.")
    except json.JSONDecodeError:
        print("❌ Error: Timetable file is corrupted or incorrectly formatted.")


# Function to add timetable
def add_timetable():
    today = datetime.today()
    start_day_index = today.weekday()
    for course_key in timetables:
        for i, day in enumerate(config_data["days"]):
            day_date = today + timedelta(days=(i - start_day_index))
            date_str = day_date.strftime('%Y-%m-%d')
            num_subjects = random.randint(5, 7)

            daily_subjects = random.sample(config_data["subjects"], num_subjects)
            daily_timeslots = config_data["timeslots"][:num_subjects]
            daily_classrooms = random.choices(config_data["classroom"], k=num_subjects)
            timetables[course_key][day] = {
                "date": date_str,
                "schedule": [
                    {"subject": subj, "time": time, "classroom": classroom}
                    for subj, time, classroom in zip(daily_subjects, daily_timeslots, daily_classrooms)
                ]
            }
    with open("timetable.json", "w") as file:
        json.dump(timetables, file, indent=4)
    print("New timetables generated and saved successfully!")

# Combination of all timetable function
def manage_timetable():
    # Load configuration
    try:
        with open("config.json", 'r') as json_file:
            config = json.load(json_file)
        print("Configuration loaded successfully!")
    except FileNotFoundError:
        print("Error: Configuration file not found!")
        return

    # Load timetables
    try:
        with open("timetable.json", 'r') as json_file:
            timetables = json.load(json_file)
        print("Existing timetables loaded successfully!")
    except FileNotFoundError:
        print("No existing timetables found, generating new timetables...")
        timetables = {}

    while True:
        print("1. Check all timetables")
        print("2. Edit a timetable")
        print("3. Generate new timetables")
        print("4. Exit")
        choice = input("Enter your choice (1, 2, 3 or 4): ")

        if choice == '1':
            view_all_timetables(timetables)

        elif choice == '2':
            print("\nWhich course would you like to edit?")
            print("1. Software Engineering")
            print("2. Computer Science")
            print("3. Data Science")
            course_choice = input("Enter the course number (1, 2, or 3): ")
            course_map = {"1": "Software Engineering", "2": "Computer Science", "3": "Data Science"}
            if course_choice not in course_map:
                print("Invalid course selection!")
                continue

            course = course_map[course_choice]
            if course not in timetables:
                print(f"No timetable found for {course}!")
                continue

            print(f"\n=== Timetable for {course} ===")
            for day, details in timetables[course].items():
                print("  " + "-" * 50)
                print(f"  Day: {day} - Date: {details.get('date', 'No Date')}")
                for entry in details.get("schedule", []):
                    print(f"    - {entry['time']}: {entry['subject']} (Room: {entry['classroom']})")
            print("  " + "-" * 50)
            print("\nTimetable display complete.")

            print(f"\nAvailable days for {course}:")
            available_days = list(timetables[course].keys())
            if not available_days:
                print("No days available for this course!")
                continue
            for i, day in enumerate(available_days, 1):
                print(f"{i}. {day}")

            try:
                day_choice = int(input("Enter the day number you want to edit (e.g., 1, 2, 3): "))
                if 1 <= day_choice <= len(available_days):
                    day = available_days[day_choice - 1]
                else:
                    print("Invalid day number entered!")
                    continue
            except ValueError:
                print("Invalid input! Please enter a number.")
                continue

            print(f"Current schedule for {day}:")
            for i, entry in enumerate(timetables[course][day]['schedule']):
                print(f"{i + 1}. {entry['subject']} at {entry['time']}")

            print("\nWhat would you like to do?")
            print("1. Add a new subject")
            print("2. Remove a subject")
            print("3. Edit a subject")
            print("4. Exit")
            action = input("Enter your choice (1, 2, 3, or 4): ")

            if action == '1':
                if len(timetables[course][day]['schedule']) < len(config["timeslots"]):
                    new_subject = input("Enter the subject to add: ")
                    new_time = input("Enter the time slot for the new subject: ")
                    timetable = timetables[course][day]['schedule']
                    if new_time in [entry['time'] for entry in timetable]:
                        print(f"Error: The time slot {new_time} is already taken!")
                    else:
                        timetable.append({"subject": new_subject, "time": new_time, "classroom": "TBD"})
                        print(f"Subject '{new_subject}' added at {new_time}!")
                else:
                    print("Error: No available timeslots to add a new subject.")

            elif action == '2':
                try:
                    subject_number = int(input(
                        f"Enter the number of the subject to remove (1-{len(timetables[course][day]['schedule'])}): ")) - 1
                    if subject_number < 0 or subject_number >= len(timetables[course][day]['schedule']):
                        print("Invalid subject number!")
                    else:
                        removed_subject = timetables[course][day]['schedule'].pop(subject_number)
                        print(f"Subject '{removed_subject['subject']}' at {removed_subject['time']} removed!")
                        with open("timetable.json", 'w') as json_file:
                            json.dump(timetables, json_file, indent=4)
                        print("Timetable updated and saved successfully!")
                except ValueError:
                    print("Invalid input! Please enter a number.")

            elif action == '3':
                try:
                    subject_number = int(input(
                        f"Enter the number of the subject to edit (1-{len(timetables[course][day]['schedule'])}): ")) - 1
                    if subject_number < 0 or subject_number >= len(timetables[course][day]['schedule']):
                        print("Invalid subject number!")
                        continue
                    field_to_edit = input("Do you want to edit the 'subject' or the 'time'? ").lower()
                    if field_to_edit == 'subject':
                        new_subject = input("Enter the new subject: ")
                        timetables[course][day]['schedule'][subject_number]['subject'] = new_subject
                        print(f"Subject updated to '{new_subject}'!")
                    elif field_to_edit == 'time':
                        new_time = input("Enter the new time: ")
                        if new_time in [entry['time'] for entry in timetables[course][day]['schedule']]:
                            print(f"Error: The time slot {new_time} is already taken!")
                        else:
                            timetables[course][day]['schedule'][subject_number]['time'] = new_time
                            print(f"Time updated to '{new_time}'!")
                    else:
                        print("Invalid option entered! Use 'subject' or 'time'.")
                except ValueError:
                    print("Invalid input! Please enter a number.")

            elif action == '4':
                print("Exiting the timetable editor.")
                with open("timetable.json", 'w') as json_file:
                    json.dump(timetables, json_file, indent=4)
                continue

            with open("timetable.json", 'w') as json_file:
                json.dump(timetables, json_file, indent=4)
            print("Timetable changes saved successfully!")

        elif choice == '3':
            print("\nWhich course would you like to regenerate?")
            print("1. Software Engineering")
            print("2. Computer Science")
            print("3. Data Science")
            course_choice = input("Enter the course number (1, 2, or 3): ")

            course_map = {"1": "Software Engineering", "2": "Computer Science", "3": "Data Science"}
            if course_choice not in course_map:
                print("Invalid course entered!")
                continue

            course = course_map[course_choice]
            timetables[course] = {}
            today = datetime.today()
            start_day_index = today.weekday()

            for i, day in enumerate(config["days"]):
                day_date = today + timedelta(days=(i - start_day_index))
                date_str = day_date.strftime('%Y-%m-%d')
                num_subjects = random.randint(5, 7)
                daily_subjects = random.sample(config["subjects"], num_subjects)
                daily_timeslots = config["timeslots"][:num_subjects]
                daily_classrooms = random.choices(config["classroom"], k=num_subjects)
                timetables[course][day] = {
                    "date": date_str,
                    "schedule": [
                        {"subject": subj, "time": time, "classroom": classroom}
                        for subj, time, classroom in zip(daily_subjects, daily_timeslots, daily_classrooms)
                    ]
                }

            with open("timetable.json", 'w') as json_file:
                json.dump(timetables, json_file, indent=4)
            print("New timetables generated and saved successfully!")

        elif choice == '4':
            print("Exiting. Goodbye!")
            break

        else:
            print("Invalid choice! Please enter 1, 2, 3, or 4.")

# Function to view all exam schedule
def view_all_exam_schedule(exam_data):
    """Display exam schedule for a user-selected course."""
    if not exam_data:
        print("No exam schedule available.")
        return

    print("\nAvailable Courses:")
    course_options = {str(i + 1): course for i, course in enumerate(exam_data.keys())}
    for num, course in course_options.items():
        print(f"{num}. {course}")

    course_selection = input("Enter the course number to view its exam schedule: ")
    if course_selection not in course_options:
        print("Invalid course selection!")
        return

    selected_course = course_options[course_selection]
    print(f"\n=== Exam Schedule for {selected_course} ===")
    for day, details in exam_data[selected_course].items():
        print("  " + "-" * 50)
        print(f"  Day: {day} - Date: {details.get('date', 'No Date')}")
        for entry in details.get("schedule", []):
            print(f"    - {entry['time']}: {entry['subject']}")
    print("  " + "-" * 50)
    print("\nExam schedule display complete.")

# Function to view exam schedule by course
def load_exam_schedule_by_course(student_course):
    """Load and display exam schedule for a specific course."""
    if not student_course:
        print("❌ Error: No course provided for the student.")
        return
    try:
        with open("exam.json", "r") as file:
            exam_schedule = json.load(file)
        print(f"📌 Available courses in exam schedule: {list(exam_schedule.keys())}")  # Fixed to use exam_schedule
        print(f"🔍 Debug: Looking for exam schedule of {student_course}")
        if student_course in exam_schedule:
            print(f"\n📅 Exam Schedule for {student_course}:\n")
            course_exam = exam_schedule[student_course]
            for day, details in course_exam.items():
                print(f"📆 {day} - {details.get('date', 'No Date')}")
                for schedule in details.get("schedule", []):
                    print(f"  ⏰ {schedule['time']}: {schedule['subject']}")
                print("\n" + "-" * 30)
        else:
            print(f"⚠ No exam schedule found for the course: {student_course}")
    except FileNotFoundError:
        print("❌ Error: Exam schedule file not found.")
    except json.JSONDecodeError:
        print("❌ Error: Exam schedule file is corrupted or incorrectly formatted.")


def add_exam_schedule(exam_data, config):
    """Generate and save a new exam schedule."""
    today = datetime.today()
    start_day_index = today.weekday()
    for course_key in exam_data:  # Assumes exam_data has course keys initialized
        for i, day in enumerate(config["days"]):  # Fixed config_data to config
            day_date = today + timedelta(days=(i - start_day_index))
            date_str = day_date.strftime('%Y-%m-%d')
            num_subjects = random.randint(1, 1)  # Generates 1 subject per day
            daily_subjects = random.sample(config["subjects"], num_subjects)
            daily_timeslots = random.sample(config["timeslots"], num_subjects)
            exam_data[course_key][day] = {
                "date": date_str,
                "schedule": [{"subject": subj, "time": time} for subj, time in zip(daily_subjects, daily_timeslots)]
            }
    with open("exam.json", "w") as file:
        json.dump(exam_data, file, indent=4)
    print("New exam schedule generated and saved successfully!")

def manage_exam_schedule():
    """Manage exam schedules with view, edit, and generate options."""
    try:
        with open("config.json", 'r') as json_file:
            config = json.load(json_file)
        print("Configuration loaded successfully!")
    except FileNotFoundError:
        print("Error: Configuration file not found!")
        return

    # Load or initialize exam_data
    try:
        with open("exam.json", 'r') as json_file:
            exam_data = json.load(json_file)
        print("Existing exam schedule loaded successfully!")
    except FileNotFoundError:
        print("No existing exam schedule found, initializing empty schedule...")
        exam_data = {"Software Engineering": {}, "Computer Science": {}, "Data Science": {}}  # Initialize with courses

    while True:
        print("1. Check all exam schedules")
        print("2. Edit an exam schedule")
        print("3. Generate new exam schedules")
        print("4. Exit")
        choice = input("Enter your choice (1, 2, 3 or 4): ")

        if choice == '1':
            view_all_exam_schedule(exam_data)

        elif choice == '2':
            print("\nWhich course would you like to edit?")
            print("1. Software Engineering")
            print("2. Computer Science")
            print("3. Data Science")
            course_choice = input("Enter the course number (1, 2, or 3): ")

            course_map = {"1": "Software Engineering", "2": "Computer Science", "3": "Data Science"}
            if course_choice not in course_map:
                print("Invalid course entered!")
                continue

            course = course_map[course_choice]
            if course not in exam_data or not exam_data[course]:
                print(f"No exam schedule found for {course}!")
                continue

            print(f"\nAvailable days for {course}:")
            available_days = list(exam_data[course].keys())
            for i, day in enumerate(available_days, 1):
                print(f"{i}. {day}")
            try:
                day_choice = int(input("Enter the day number to edit (e.g., 1, 2, 3): "))
                if 1 <= day_choice <= len(available_days):
                    day = available_days[day_choice - 1]
                else:
                    print("Invalid day number!")
                    continue
            except ValueError:
                print("Invalid input! Please enter a number.")
                continue

            print(f"Current exam schedule for {day}:")
            for i, entry in enumerate(exam_data[course][day]['schedule']):
                print(f"{i + 1}. {entry['subject']} at {entry['time']}")

            print("\nWhat would you like to do?")
            print("1. Edit a subject")
            print("2. Exit")
            action = input("Enter your choice (1 or 2): ")  # Fixed options to match implemented actions

            if action == '1':
                try:
                    subject_number = int(input(
                        f"Enter the number of the subject to edit (1-{len(exam_data[course][day]['schedule'])}): ")) - 1
                    if subject_number < 0 or subject_number >= len(exam_data[course][day]['schedule']):
                        print("Invalid subject number!")
                        continue
                    field_to_edit = input("Do you want to edit the 'subject' or the 'time'? ").lower()
                    if field_to_edit == 'subject':
                        new_subject = input("Enter the new subject: ")
                        exam_data[course][day]['schedule'][subject_number]['subject'] = new_subject
                        print(f"Subject updated to '{new_subject}'!")
                    elif field_to_edit == 'time':
                        new_time = input("Enter the new time: ")
                        if new_time in [entry['time'] for entry in exam_data[course][day]['schedule']]:
                            print(f"Error: The time slot {new_time} is already taken!")
                        else:
                            exam_data[course][day]['schedule'][subject_number]['time'] = new_time
                            print(f"Time updated to '{new_time}'!")
                    else:
                        print("Invalid option entered! Use 'subject' or 'time'.")
                except ValueError:
                    print("Invalid input! Please enter a number.")

            elif action == '2':
                print("Exiting the exam schedule editor.")
                with open("exam.json", 'w') as json_file:
                    json.dump(exam_data, json_file, indent=4)
                continue  # Return to main menu

            # Save changes after edit
            with open("exam.json", 'w') as json_file:
                json.dump(exam_data, json_file, indent=4)
            print("Exam schedule changes saved successfully!")

        elif choice == '3':
            add_exam_schedule(exam_data, config)  # Pass exam_data and config explicitly

        elif choice == '4':
            print("Exiting. Goodbye!")
            break

        else:
            print("Invalid choice! Please enter 1, 2, 3, or 4.")


# Function for teacher to add or modify attendance
def add_attendance():
    # Load timetable data
    try:
        with open("timetable.json", "r") as file:
            timetable_data = json.load(file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("Timetable data not found or corrupted.")
        return

    # Load student data to get enrolled courses
    try:
        with open("student.json", "r") as file:
            student_data = json.load(file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("Student data not found or corrupted.")
        return

    # Load existing attendance data
    try:
        with open("attendance.json", "r") as file:
            attendance_data = json.load(file)
    except (FileNotFoundError, json.JSONDecodeError):
        attendance_data = {}

    # Display available courses
    print("Select a course from the timetable:")
    course_options = list(timetable_data.keys())
    for i, course in enumerate(course_options, 1):
        print(f"{i}. {course}")

    try:
        course_choice = int(input("Enter course number 1-{0}: ".format(len(course_options))))
        if 1 <= course_choice <= len(course_options):
            selected_course = course_options[course_choice - 1]
        else:
            print("Invalid course number.")
            return
    except ValueError:
        print("Invalid input. Please enter a number.")
        return

    # Get current date or allow manual selection
    current_date = datetime.now().strftime("%Y-%m-%d")
    use_current = input(f"Use current date {current_date}? y/n: ").strip().lower()
    if use_current == 'y':
        selected_date = current_date
        # Find the day in timetable that matches the current date
        selected_day = None
        for day, details in timetable_data[selected_course].items():
            if details["date"] == selected_date:
                selected_day = day
                break
        if not selected_day:
            print(f"No timetable entry found for {selected_date} in {selected_course}.")
            return
    else:
        print("Available days:")
        day_options = list(timetable_data[selected_course].keys())
        for i, day in enumerate(day_options, 1):
            print(f"{i}. {day} ({timetable_data[selected_course][day]['date']})")
        try:
            day_choice = int(input("Enter day number 1-{0}: ".format(len(day_options))))
            if 1 <= day_choice <= len(day_options):
                selected_day = day_options[day_choice - 1]
                selected_date = timetable_data[selected_course][selected_day]["date"]
            else:
                print("Invalid day number.")
                return
        except ValueError:
            print("Invalid input. Please enter a number.")
            return

    # Display subjects for the selected date using selected_day
    schedule = timetable_data[selected_course][selected_day]["schedule"]
    print(f"Subjects for {selected_course} on {selected_date}:")
    for i, entry in enumerate(schedule, 1):
        print(f"{i}. {entry['subject']} ({entry['time']})")

    try:
        subject_choice = int(input("Enter subject number 1-{0}: ".format(len(schedule))))
        if 1 <= subject_choice <= len(schedule):
            selected_subject = schedule[subject_choice - 1]["subject"]
            selected_time = schedule[subject_choice - 1]["time"]
        else:
            print("Invalid subject number.")
            return
    except ValueError:
        print("Invalid input. Please enter a number.")
        return

    # Get students enrolled in the selected course
    enrolled_students = [s["Student ID"] for s in student_data if s["course"] == selected_course]
    if not enrolled_students:
        print(f"No students enrolled in {selected_course}.")
        return

    # Initialize attendance for this session if not present
    if selected_course not in attendance_data:
        attendance_data[selected_course] = {}
    if selected_subject not in attendance_data[selected_course]:
        attendance_data[selected_course][selected_subject] = {}
    if selected_date not in attendance_data[selected_course][selected_subject]:
        attendance_data[selected_course][selected_subject][selected_date] = {
            "time": selected_time,
            "students": {student_id: "absent" for student_id in enrolled_students},
            "otp": None
        }

    session_data = attendance_data[selected_course][selected_subject][selected_date]

    # Ask teacher how to take attendance
    print(f"Attendance for {selected_course} - {selected_subject} on {selected_date} at {selected_time}:")
    method = input("Enter 1 for OTP, 2 for manual, 3 to modify existing: ").strip()

    if method == '1':
        # Generate OTP
        session_data["otp"] = generate_otp()
        print(f"OTP generated: {session_data['otp']}. Share this with students.")
    elif method == '2':
        # Manual marking
        print("Mark attendance manually:")
        for student_id in enrolled_students:
            status = input(f"{student_id} (1 for present, 0 for absent): ").strip()
            session_data["students"][student_id] = "present" if status == "1" else "absent"
        session_data["otp"] = None  # No OTP for manual marking
    elif method == '3':
        # Modify existing attendance
        print("Current attendance:")
        for student_id, status in session_data["students"].items():
            print(f"{student_id}: {status}")
        student_to_modify = input("Enter Student ID to modify: ").strip()
        if student_to_modify in session_data["students"]:
            new_status = input("Enter new status (1 for present, 0 for absent): ").strip()
            session_data["students"][student_to_modify] = "present" if new_status == "1" else "absent"
            print(f"Attendance for {student_to_modify} updated.")
        else:
            print("Student ID not found.")
            return
    else:
        print("Invalid option.")
        return

    # Save updated attendance
    try:
        with open("attendance.json", "w") as file:
            json.dump(attendance_data, file, indent=4)
        print("Attendance data saved successfully.")
    except IOError as e:
        print(f"Error saving attendance data: {e}")


# Function for students to mark attendance using OTP
def mark_attendance(student_id):
    # Load student data to verify course
    try:
        with open("student.json", "r") as file:
            student_data = json.load(file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("Student data not found or corrupted.")
        return

    # Load attendance data
    try:
        with open("attendance.json", "r") as file:
            attendance_data = json.load(file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("No attendance records available.")
        return

    # Get student's enrolled course
    student_course = None
    for student in student_data:
        if student["Student ID"] == student_id:
            student_course = student["course"]
            break
    if not student_course:
        print(f"Student ID '{student_id}' not found.")
        return

    # Check if there’s an active session for the student’s course
    if student_course not in attendance_data:
        print(f"No attendance sessions available for {student_course}.")
        return

    # Ask for OTP
    otp_input = input("Enter the 3-digit OTP: ").strip()

    # Find matching OTP in today’s sessions
    current_date = datetime.now().strftime("%Y-%m-%d")
    found = False
    for subject, dates in attendance_data[student_course].items():
        if current_date in dates:
            session_data = dates[current_date]
            if session_data["otp"] == otp_input and student_id in session_data["students"]:
                session_data["students"][student_id] = "present"
                found = True
                print(f"Attendance marked as present for {student_id} in {student_course} - {subject}.")
                break
    if not found:
        print("Invalid OTP or no matching session found for today.")

    # Save updated attendance
    try:
        with open("attendance.json", "w") as file:
            json.dump(attendance_data, file, indent=4)
    except IOError as e:
        print(f"Error saving attendance: {e}")


# Function to view attendance
def view_attendance(student_id):
    # Load student data to get the student's course
    try:
        with open("student.json", "r") as student_file:
            student_data = json.load(student_file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("Student data not found or corrupted.")
        return

    # Load attendance data
    try:
        with open("attendance.json", "r") as attendance_file:
            attendance_data = json.load(attendance_file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("No attendance records available.")
        return

    # Get student's enrolled course
    student_course = None
    student_name = None
    for student in student_data:
        if student["Student ID"] == student_id:
            student_course = student["course"]
            student_name = student["name"]
            break
    if not student_course:
        print(f"Student ID '{student_id}' not found.")
        return

    # Check if attendance exists for the student's course
    if student_course not in attendance_data:
        print(f"No attendance records found for {student_course}.")
        return

    print(f"\n=== Attendance for {student_id} - {student_name} in {student_course} ===")

    # Variables to calculate attendance percentage
    total_sessions = 0
    present_sessions = 0

    # Iterate through all subjects and dates for the student's course
    for subject, dates in attendance_data[student_course].items():
        print(f"\nSubject: {subject}")
        for date, session in dates.items():
            print(f"  Date: {date} | Time: {session['time']}")
            status = session["students"].get(student_id, "absent")  # Default to absent if not listed
            print(f"    Status: {status}")
            total_sessions += 1
            if status == "present":
                present_sessions += 1

    # Calculate and display attendance percentage
    if total_sessions > 0:
        percentage = (present_sessions / total_sessions) * 100
        print(f"\nTotal Sessions: {total_sessions}")
        print(f"Present Sessions: {present_sessions}")
        print(f"Attendance Percentage: {percentage:.2f}%")
    else:
        print("\nNo attendance sessions recorded for this student.")

    print("===")


# Function for teachers to view student attendance
def view_student_attendance_teacher():
    # Load timetable data
    try:
        with open("timetable.json", "r") as timetable_file:
            timetable_data = json.load(timetable_file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("Timetable data not found or corrupted.")
        return

    # Load student data
    try:
        with open("student.json", "r") as student_file:
            student_data = json.load(student_file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("Student data not found or corrupted.")
        return

    # Load attendance data
    try:
        with open("attendance.json", "r") as attendance_file:
            attendance_data = json.load(attendance_file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("No attendance records available.")
        return

    # Step 1: Select course
    print("Available courses:")
    course_options = list(timetable_data.keys())
    for i, course in enumerate(course_options, 1):
        print(f"{i}. {course}")

    try:
        course_choice = int(input("Enter course number 1-{0}: ".format(len(course_options))))
        if 1 <= course_choice <= len(course_options):
            selected_course = course_options[course_choice - 1]
        else:
            print("Invalid course number.")
            return
    except ValueError:
        print("Invalid input. Please enter a number.")
        return

    # Step 2: Select student with attendance records
    enrolled_students = [s for s in student_data if s["course"] == selected_course]
    if not enrolled_students:
        print(f"No students enrolled in {selected_course}.")
        return

    # Filter students with attendance records
    students_with_attendance = []
    for student in enrolled_students:
        student_id = student["Student ID"]
        if selected_course in attendance_data and any(
                student_id in dates["students"] for subject in attendance_data[selected_course].values() for dates in
                subject.values()):
            students_with_attendance.append(student)

    if not students_with_attendance:
        print(f"No students with attendance records in {selected_course}.")
        return

    print(f"Students with attendance in {selected_course}:")
    for i, student in enumerate(students_with_attendance, 1):
        print(f"{i}. {student['Student ID']} - {student['name']}")

    try:
        student_choice = int(input("Enter student number 1-{0}: ".format(len(students_with_attendance))))
        if 1 <= student_choice <= len(students_with_attendance):
            selected_student = students_with_attendance[student_choice - 1]
            student_id = selected_student["Student ID"]
            student_name = selected_student["name"]
        else:
            print("Invalid student number.")
            return
    except ValueError:
        print("Invalid input. Please enter a number.")
        return

    # Display attendance for the selected student
    print(f"\n=== Attendance for {student_id} - {student_name} in {selected_course} ===")

    total_sessions = 0
    present_sessions = 0

    for subject, dates in attendance_data.get(selected_course, {}).items():
        print(f"\nSubject: {subject}")
        for date, session in dates.items():
            if student_id in session["students"]:
                print(f"  Date: {date} | Time: {session['time']}")
                status = session["students"][student_id]
                print(f"    Status: {status}")
                total_sessions += 1
                if status == "present":
                    present_sessions += 1

    if total_sessions > 0:
        percentage = (present_sessions / total_sessions) * 100
        print(f"\nTotal Sessions: {total_sessions}")
        print(f"Present Sessions: {present_sessions}")
        print(f"Attendance Percentage: {percentage:.2f}%")
    else:
        print("\nNo attendance sessions recorded for this student in this course.")

    print("===")


# Function to display student's fees
def view_fees(student_id):
    """View exam fees for a student's course based on their Student ID."""
    # Load student data
    try:
        with open("student.json", "r") as student_file:
            student_records = json.load(student_file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("Error: Student records not found or invalid.")
        return

    # Find student's course
    student_course = None
    for student in student_records:
        if student.get("Student ID") == student_id:
            student_course = student.get("course")
            break

    if not student_course:
        print(f"Error: No student found with Student ID '{student_id}'.")
        return

    # Load fees data
    try:
        with open("fees.json", "r") as fees_file:
            fees_records = json.load(fees_file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("Error: Fee records not found or invalid.")
        return

    if not fees_records.get("fees", []):
        print("No fee records available.")
        return

    # Find and display fees for the student's course
    found = False
    for fee_entry in fees_records.get("fees", []):
        if fee_entry.get("course_name") == student_course:
            found = True
            print(f"\nExam Fees for {student_course} (Student ID: {student_id}):")
            if not fee_entry.get("course", []):
                print("  No fee records found for this course.")
                break
            for i, course_entry in enumerate(fee_entry["course"], 1):
                print(f"  {i}. Due Date: {course_entry['due_date']}")
                print(f"     Amount: {course_entry['fees_amount']}")
            break

    if not found:
        print(f"No fee records found for course '{student_course}'.")


# Function to add fees
def add_fees():
    """Add a new fee record for a course without student TP."""
    try:
        with open("fees.json", "r") as file:
            fees_records = json.load(file)
    except FileNotFoundError:
        fees_records = {"fees": []}

    # Display course options
    print("\nChoose the student's course:")
    print("1. Software Engineering")
    print("2. Computer Science")
    print("3. Data Science")

    # Get course choice
    course_choice = input("Enter the course number (1, 2, or 3): ")
    course_map = {
        "1": "Software Engineering",
        "2": "Computer Science",
        "3": "Data Science"
    }

    # Validate course choice
    if course_choice not in course_map:
        print("Invalid course number! Please choose 1, 2, or 3.")
        return

    course_name = course_map[course_choice]

    # Check if course already exists in fees_records
    course_found = False
    for fee_course in fees_records["fees"]:
        if fee_course.get("course_name") == course_name:
            course_found = True
            break
    if not course_found:
        fees_records["fees"].append({"course_name": course_name, "course": []})

    # Prompt for remaining fee details
    due_date = input("Enter the due date (e.g., 2025-02-28): ")
    amount = input("Enter the fees amount (e.g., RM9,999): ")

    # Add fee entry to the course
    for fee_course in fees_records["fees"]:
        if fee_course["course_name"] == course_name:
            fee_course["course"].append({
                "due_date": due_date,
                "fees_amount": amount
            })
            break

    # Save to file
    with open("fees.json", "w") as file:
        json.dump(fees_records, file, indent=4)

    print(f"Fee details for {course_name} have been added successfully.")

def check_fee_status():
    """Check the payment status of a student's fees."""
    try:
        with open("fees.json", "r") as file:
            fees_records = json.load(file)
    except FileNotFoundError:
        print("Error: No fees records found.")
        return

    if not fees_records.get("fees", []):
        print("No fee records available.")
        return

    course_name = input("Please input the student's course: ")
    student_id = input("Please Enter the Student's TP number: ")

    course_found = False
    fee_found = False
    for fee_course in fees_records["fees"]:
        if fee_course.get("course_name") == course_name:
            course_found = True
            for fee_entry in fee_course["course"]:
                if fee_entry["student_id"] == student_id:
                    fee_found = True
                    status = fee_entry.get("status", "Unpaid")  # Default to "Unpaid" if missing
                    print(f"\nFee Status for {student_id} in {course_name}:")
                    print(f"  Due Date: {fee_entry['due_date']}")
                    print(f"  Amount: {fee_entry['fees_amount']}")
                    print(f"  Status: {status}")
                    break
            break

    if not course_found:
        print(f"Error: Course '{course_name}' not found in fees records.")
    elif not fee_found:
        print(f"Error: No fee record found for student '{student_id}' in course '{course_name}'.")

def delete_fees():
    """Delete a fee record by selecting from a list of fees for a course."""
    # Load fees data
    try:
        with open("fees.json", "r") as file:
            fees_records = json.load(file)
    except FileNotFoundError:
        print("Error: No fees records found. Nothing to delete.")
        return

    if not fees_records.get("fees", []):
        print("No fee records available to delete.")
        return

    # Display course options
    print("\nChoose the course to delete a fee from:")
    print("1. Software Engineering")
    print("2. Computer Science")
    print("3. Data Science")

    # Get course choice
    course_choice = input("Enter the course number (1, 2, or 3): ")
    course_map = {
        "1": "Software Engineering",
        "2": "Computer Science",
        "3": "Data Science"
    }

    # Validate course choice
    if course_choice not in course_map:
        print("Invalid course number! Please choose 1, 2, or 3.")
        return

    course_name = course_map[course_choice]

    # Find the course and display its fees
    course_found = False
    for fee_course in fees_records["fees"]:
        if fee_course.get("course_name") == course_name:
            course_found = True
            if not fee_course["course"]:
                print(f"No fee records found for course '{course_name}'.")
                return

            # Display all fees with numbers
            print(f"\nFee records for {course_name}:")
            for i, fee_entry in enumerate(fee_course["course"], 1):
                print(f"{i}. Due Date: {fee_entry['due_date']}, Amount: {fee_entry['fees_amount']}")

            # Prompt user to select a fee to delete
            try:
                fee_choice = int(input(f"Enter the number of the fee to delete (1-{len(fee_course['course'])}): "))
                if 1 <= fee_choice <= len(fee_course["course"]):
                    del fee_course["course"][fee_choice - 1]  # Delete the selected fee
                    # Clean up empty course
                    if not fee_course["course"]:
                        fees_records["fees"].remove(fee_course)
                    with open("fees.json", "w") as file:
                        json.dump(fees_records, file, indent=4)
                    print(f"Fee record {fee_choice} in course '{course_name}' has been deleted successfully.")
                else:
                    print(f"Invalid number! Please choose between 1 and {len(fee_course['course'])}.")
            except ValueError:
                print("Invalid input! Please enter a number.")
            break

    if not course_found:
        print(f"Error: Course '{course_name}' not found in fees records.")

def edit_fees():
    """Edit a fee record by selecting from a list of fees for a course, choosing field with 1 or 2."""
    # Load fees data
    try:
        with open("fees.json", "r") as file:
            fees_records = json.load(file)
    except FileNotFoundError:
        print("Error: No fees records found. Nothing to edit.")
        return

    if not fees_records.get("fees", []):
        print("No fee records available to edit.")
        return

    # Display course options
    print("\nChoose the course to edit a fee from:")
    print("1. Software Engineering")
    print("2. Computer Science")
    print("3. Data Science")

    # Get course choice
    course_choice = input("Enter the course number (1, 2, or 3): ")
    course_map = {
        "1": "Software Engineering",
        "2": "Computer Science",
        "3": "Data Science"
    }

    # Validate course choice
    if course_choice not in course_map:
        print("Invalid course number! Please choose 1, 2, or 3.")
        return

    course_name = course_map[course_choice]

    # Find the course and display its fees
    course_found = False
    for fee_course in fees_records["fees"]:
        if fee_course.get("course_name") == course_name:
            course_found = True
            if not fee_course["course"]:
                print(f"No fee records found for course '{course_name}'.")
                return

            # Display all fees with numbers
            print(f"\nFee records for {course_name}:")
            for i, fee_entry in enumerate(fee_course["course"], 1):
                print(f"{i}. Due Date: {fee_entry['due_date']}, Amount: {fee_entry['fees_amount']}")

            # Prompt user to select a fee to edit
            try:
                fee_choice = int(input(f"Enter the number of the fee to edit (1-{len(fee_course['course'])}): "))
                if 1 <= fee_choice <= len(fee_course["course"]):
                    fee_entry = fee_course["course"][fee_choice - 1]  # Get the selected fee
                    print(f"\nCurrent Fee Details for entry {fee_choice} in {course_name}:")
                    print(f"  Due Date: {fee_entry['due_date']}")
                    print(f"  Amount: {fee_entry['fees_amount']}")

                    # Display field options
                    print("\nWhat would you like to edit?")
                    print("1. Due Date")
                    print("2. Amount")
                    field_choice = input("Enter the number of the field to edit (1 or 2): ")

                    # Handle field edit based on numeric choice
                    if field_choice == "1":
                        new_due_date = input("Enter the new due date (e.g., 2025-02-28): ")
                        fee_entry["due_date"] = new_due_date
                        print(f"Due date updated to {new_due_date}.")
                    elif field_choice == "2":
                        new_amount = input("Enter the new fees amount (e.g., RM9,999): ")
                        fee_entry["fees_amount"] = new_amount
                        print(f"Amount updated to {new_amount}.")
                    else:
                        print("Invalid field number! Please choose 1 or 2.")
                        return  # Exit if invalid field choice

                    # Save changes
                    with open("fees.json", "w") as file:
                        json.dump(fees_records, file, indent=4)
                    print("Fee record updated successfully.")
                else:
                    print(f"Invalid number! Please choose between 1 and {len(fee_course['course'])}.")
            except ValueError:
                print("Invalid input! Please enter a number.")
            break

    if not course_found:
        print(f"Error: Course '{course_name}' not found in fees records.")

def fee_system():
    while True:
        print("\n--- Fee System ---")
        print("1. View Student Fees")
        print("2. Add Fees")
        print("3. Edit Fees")
        print("4. Delete Fees")
        print("0. Exit")

        choice = input("Enter your choice: ")

        if choice == "1":
            check_fee_status()

        elif choice == '2':
            add_fees()

        elif choice == "3":
            edit_fees()

        elif choice == "4":
            delete_fees()

        elif choice == "0":
            break

        else:
            print("Invalid choice! Please enter a valid option.")


# Function to add student's results
def add_results():
    # Load existing results data
    try:
        with open("results.json", "r") as add_results_file:
            add_results_data = json.load(add_results_file)
    except (FileNotFoundError, json.JSONDecodeError):
        add_results_data = {}

    # Load timetable data
    try:
        with open("timetable.json", "r") as timetable_file:
            timetable_data = json.load(timetable_file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("Timetable data not found or corrupted.")
        return

    # Load student data
    try:
        with open("student.json", "r") as student_file:
            student_data = json.load(student_file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("Student data not found or corrupted.")
        return

    # Step 1: Select course from timetable.json
    print("Available courses:")
    course_options = list(timetable_data.keys())
    for i, course in enumerate(course_options, 1):
        print(f"{i}. {course}")

    try:
        course_choice = int(input("Enter course number 1-{0}: ".format(len(course_options))))
        if 1 <= course_choice <= len(course_options):
            selected_course = course_options[course_choice - 1]
        else:
            print("Invalid course number.")
            return
    except ValueError:
        print("Invalid input. Please enter a number.")
        return

    # Step 2: Select student enrolled in the course from student.json
    enrolled_students = [s for s in student_data if s["course"] == selected_course]
    if not enrolled_students:
        print(f"No students enrolled in {selected_course}.")
        return

    print(f"Students enrolled in {selected_course}:")
    for i, student in enumerate(enrolled_students, 1):
        print(f"{i}. {student['Student ID']} - {student['name']}")

    try:
        student_choice = int(input("Enter student number 1-{0}: ".format(len(enrolled_students))))
        if 1 <= student_choice <= len(enrolled_students):
            selected_student = enrolled_students[student_choice - 1]
            student_id = selected_student["Student ID"]
        else:
            print("Invalid student number.")
            return
    except ValueError:
        print("Invalid input. Please enter a number.")
        return

    # Step 3: Select subject from timetable.json for the course
    # Collect unique subjects across all days for the selected course
    all_subjects = set()
    for day in timetable_data[selected_course].values():
        for schedule in day["schedule"]:
            all_subjects.add(schedule["subject"])
    subject_options = list(all_subjects)

    print(f"Available subjects for {selected_course}:")
    for i, subject in enumerate(subject_options, 1):
        print(f"{i}. {subject}")

    try:
        subject_choice = int(input("Enter subject number 1-{0}: ".format(len(subject_options))))
        if 1 <= subject_choice <= len(subject_options):
            selected_subject = subject_options[subject_choice - 1]
        else:
            print("Invalid subject number.")
            return
    except ValueError:
        print("Invalid input. Please enter a number.")
        return

    # Step 4: Input marks
    try:
        marks = int(input(f"Enter the score for {selected_subject} (0-100): "))
        if 0 <= marks <= 100:
            # Proceed with adding/updating results
            pass
        else:
            print("Marks must be between 0 and 100.")
            return
    except ValueError:
        print("Invalid input. Please enter a number.")
        return

    # Add or update results in results.json
    if student_id not in add_results_data:
        add_results_data[student_id] = {
            "student_id": student_id,
            "course": selected_course,
            "results": []
        }

    # Check if the subject already exists and update marks if necessary
    for result in add_results_data[student_id]["results"]:
        if result["subject"] == selected_subject:
            result["marks"] = marks
            break
    else:
        add_results_data[student_id]["results"].append({
            "subject": selected_subject,
            "marks": marks
        })

    # Save updated results
    try:
        with open("results.json", "w") as add_results_file:
            json.dump(add_results_data, add_results_file, indent=4)
        print(f"Results updated successfully for {student_id} in {selected_subject}.")
    except IOError as e:
        print(f"Error saving results: {e}")


# Function to edit student's results
def edit_results():
    # Load existing results data
    try:
        with open("results.json", "r") as edit_results_file:
            edit_results_data = json.load(edit_results_file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("No results data found.")
        return

    # Load timetable data for courses
    try:
        with open("timetable.json", "r") as timetable_file:
            timetable_data = json.load(timetable_file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("Timetable data not found or corrupted.")
        return

    # Load student data
    try:
        with open("student.json", "r") as student_file:
            student_data = json.load(student_file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("Student data not found or corrupted.")
        return

    # Step 1: Select course from timetable.json
    print("Available courses:")
    course_options = list(timetable_data.keys())
    for i, course in enumerate(course_options, 1):
        print(f"{i}. {course}")

    try:
        course_choice = int(input("Enter course number 1-{0}: ".format(len(course_options))))
        if 1 <= course_choice <= len(course_options):
            selected_course = course_options[course_choice - 1]
        else:
            print("Invalid course number.")
            return
    except ValueError:
        print("Invalid input. Please enter a number.")
        return

    # Step 2: Select student enrolled in the course from student.json
    enrolled_students = [s for s in student_data if s["course"] == selected_course]
    if not enrolled_students:
        print(f"No students enrolled in {selected_course}.")
        return

    print(f"Students enrolled in {selected_course}:")
    for i, student in enumerate(enrolled_students, 1):
        print(f"{i}. {student['Student ID']} - {student['name']}")

    try:
        student_choice = int(input("Enter student number 1-{0}: ".format(len(enrolled_students))))
        if 1 <= student_choice <= len(enrolled_students):
            selected_student = enrolled_students[student_choice - 1]
            student_id = selected_student["Student ID"]
        else:
            print("Invalid student number.")
            return
    except ValueError:
        print("Invalid input. Please enter a number.")
        return

    # Step 3: Check if student has results
    if student_id not in edit_results_data:
        print(f"No results found for {student_id}.")
        return

    # Step 4: Select subject from existing results for the student
    result_options = edit_results_data[student_id]["results"]
    if not result_options:
        print(f"No subjects recorded for {student_id}.")
        return

    print(f"Existing results for {student_id}:")
    for i, result in enumerate(result_options, 1):
        print(f"{i}. {result['subject']} - Current Marks: {result['marks']}")

    try:
        result_choice = int(input("Enter result number to edit 1-{0}: ".format(len(result_options))))
        if 1 <= result_choice <= len(result_options):
            selected_result = result_options[result_choice - 1]
            selected_subject = selected_result["subject"]
        else:
            print("Invalid result number.")
            return
    except ValueError:
        print("Invalid input. Please enter a number.")
        return

    # Step 5: Input new marks
    try:
        new_marks = int(input(f"Enter new score for {selected_subject} (0-100): "))
        if 0 <= new_marks <= 100:
            selected_result["marks"] = new_marks
        else:
            print("Marks must be between 0 and 100.")
            return
    except ValueError:
        print("Invalid input. Please enter a number.")
        return

    # Save updated results
    try:
        with open("results.json", "w") as edit_results_file:
            json.dump(edit_results_data, edit_results_file, indent=4)
        print(f"Results updated successfully for {student_id} in {selected_subject}.")
    except IOError as e:
        print(f"Error saving results: {e}")


# Function to view results according to student course and name
def view_results(student_tp):
    try:
        with open("results.json", "r") as view_results_file:
            results_data = json.load(view_results_file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("No results found.")
        return

    if student_tp in results_data:
        student_info = results_data[student_tp]
        print("-" * 50)
        print(f"Results for {student_info['student_id']}:")
        print(f"Course: {student_info['course']}")
        print("Subjects and Marks:")

        # Calculate total score and number of subjects
        total_score = 0
        num_subjects = len(student_info["results"])

        if num_subjects == 0:
            print(" - No subjects recorded yet.")
            return

        # Display individual results and accumulate total score
        for result in student_info["results"]:
            print(f" - {result['subject']}: {result['marks']}")
            total_score += result["marks"]

        # Calculate and display average score
        average_score = total_score / num_subjects
        print(f"\nAverage Score: {average_score:.2f}")

        # Determine and display grade based on boundaries
        if 90 <= average_score <= 100:
            print("Grade: A")
            print("-" * 50)
        elif 80 <= average_score < 90:
            print("Grade: B")
            print("-" * 50)
        elif 70 <= average_score < 80:
            print("Grade: C")
            print("-" * 50)
        elif 60 <= average_score < 70:
            print("Grade: D")
            print("-" * 50)
        elif 0 <= average_score < 60:
            print("Grade: F")
            print("-" * 50)
        else:
            print("Invalid Average Score (out of range)")
    else:
        print(f"No records found for Student ID {student_tp}.")


# Modified function to view results with course and student selection
def view_all_results():
    # Load timetable data for courses
    try:
        with open("timetable.json", "r") as timetable_file:
            timetable_data = json.load(timetable_file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("Timetable data not found or corrupted.")
        return

    # Load student data
    try:
        with open("student.json", "r") as student_file:
            student_data = json.load(student_file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("Student data not found or corrupted.")
        return

    # Load results data
    try:
        with open("results.json", "r") as view_results_file:
            results_data = json.load(view_results_file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("No results found.")
        return

    # Step 1: Select course
    print("Available courses:")
    course_options = list(timetable_data.keys())
    for i, course in enumerate(course_options, 1):
        print(f"{i}. {course}")

    try:
        course_choice = int(input("Enter course number 1-{0}: ".format(len(course_options))))
        if 1 <= course_choice <= len(course_options):
            selected_course = course_options[course_choice - 1]
        else:
            print("Invalid course number.")
            return
    except ValueError:
        print("Invalid input. Please enter a number.")
        return

    # Step 2: Select student
    enrolled_students = [s for s in student_data if s["course"] == selected_course]
    if not enrolled_students:
        print(f"No students enrolled in {selected_course}.")
        return

    print(f"Students enrolled in {selected_course}:")
    for i, student in enumerate(enrolled_students, 1):
        print(f"{i}. {student['Student ID']} - {student['name']}")

    try:
        student_choice = int(input("Enter student number 1-{0}: ".format(len(enrolled_students))))
        if 1 <= student_choice <= len(enrolled_students):
            selected_student = enrolled_students[student_choice - 1]
            student_tp = selected_student["Student ID"]
        else:
            print("Invalid student number.")
            return
    except ValueError:
        print("Invalid input. Please enter a number.")
        return

    # Step 3: Display results for the selected student
    if student_tp in results_data:
        student_info = results_data[student_tp]
        print("-" * 50)
        print(f"Results for {student_info['student_id']}:")
        print(f"Course: {student_info['course']}")
        print("Subjects and Marks:")

        total_score = 0
        num_subjects = len(student_info["results"])

        if num_subjects == 0:
            print(" - No subjects recorded yet.")
            return

        for result in student_info["results"]:
            print(f" - {result['subject']}: {result['marks']}")
            total_score += result["marks"]

        average_score = total_score / num_subjects
        print(f"\nAverage Score: {average_score:.2f}")

        if 90 <= average_score <= 100:
            print("Grade: A")
            print("-" * 50)
        elif 80 <= average_score < 90:
            print("Grade: B")
            print("-" * 50)
        elif 70 <= average_score < 80:
            print("Grade: C")
            print("-" * 50)
        elif 60 <= average_score < 70:
            print("Grade: D")
            print("-" * 50)
        elif 0 <= average_score < 60:
            print("Grade: F")
            print("-" * 50)
        else:
            print("Invalid Average Score (out of range)")
    else:
        print(f"No records found for Student ID {student_tp}.")


# Function to add feedback
def add_feedback():
    # Load teacher data from teacher.json
    try:
        with open("teacher.json", "r") as teacher_file:
            teacher_data = json.load(teacher_file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("Teacher data not found or corrupted.")
        return

    # Check if teacher data is empty
    if not teacher_data:
        print("No teachers available to provide feedback for.")
        return

    # Display available teachers with numbers
    print("Available teachers:")
    teacher_names = [teacher["name"] for teacher in teacher_data]
    for i, teacher in enumerate(teacher_names, 1):
        print(f"{i}. {teacher}")

    # Get user's teacher choice
    try:
        choice = int(input("Enter the number of the teacher to comment on: "))
        if 1 <= choice <= len(teacher_names):
            feedback_teacher = teacher_names[choice - 1]
        else:
            print(f"Please enter a number between 1 and {len(teacher_names)}.")
            return
    except ValueError:
        print("Invalid input. Please enter a number.")
        return

    # Load config data from config.json
    try:
        with open("config.json", "r") as config_file:
            config_data = json.load(config_file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("Config data not found or corrupted.")
        return

    # Check if subjects exist and are not empty
    if "subjects" not in config_data or not config_data["subjects"]:
        print("No subjects available to provide feedback for.")
        return

    # Display available subjects with numbers
    print("Available subjects:")
    subjects = config_data["subjects"]
    for i, subject in enumerate(subjects, 1):
        print(f"{i}. {subject}")

    # Get user's subject choice
    try:
        subject_choice = int(input("Enter the number of the subject: "))
        if 1 <= subject_choice <= len(subjects):
            selected_subject = subjects[subject_choice - 1]
        else:
            print(f"Please enter a number between 1 and {len(subjects)}.")
            return
    except ValueError:
        print("Invalid input. Please enter a number.")
        return

    # Load existing feedback data (original logic preserved)
    try:
        with open("feedback.json", "r") as feedback_file:
            feedback_data = json.load(feedback_file)
    except (FileNotFoundError, json.JSONDecodeError):
        feedback_data = {}

    # Collect feedback text (original logic adapted)
    feedback_text = input("Type in your feedback: ")

    # Add feedback to the selected teacher (original logic preserved)
    if feedback_teacher not in feedback_data:
        feedback_data[feedback_teacher] = {
            "subject": selected_subject,
            "feedback": []
        }

    feedback_data[feedback_teacher]["feedback"].append(feedback_text)

    # Save updated feedback (original logic preserved)
    with open("feedback.json", "w") as feedback_file:
        json.dump(feedback_data, feedback_file, indent=4)
    print(f"Feedback added successfully for {feedback_teacher}.")


# Function for staff to view teacher feedback
def view_feedback_staff():
    try:
        with open("feedback.json", "r") as feedback_file:
            feedback_data = json.load(feedback_file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("No feedback found.")
        return

    feedback_teacher_name = input("Enter Teacher's name: ").capitalize()  # User input here
    if feedback_teacher_name in feedback_data:
        feedback_info = feedback_data[feedback_teacher_name]
        print(f"Course: {feedback_info['course']}")
        print("Feedback:")
        for feedback in feedback_info["feedback"]:
            print(f" - {feedback}")
    else:
        print("No records found for the given teacher name.")


# Function for teachers to view feedback
def view_feedback_teacher():
    teacher = teacher_name
    try:
        with open("feedback.json", "r") as feedback_file:
            feedback_data = json.load(feedback_file)
    except (FileNotFoundError, json.JSONDecodeError):
        print("No feedback found.")
        return
    if teacher in feedback_data:
        feedback_info = feedback_data[teacher]
        print(f"Subject: {feedback_info['subject']}")
        print("Feedback:")
        for feedback in feedback_info["feedback"]:
            print(f" - {feedback}")
    else:
        print("No records found for the given teacher name.")


# Function to save data to JSON files
def save_data(file_name, message_data):
    with open(file_name, "w") as file:
        json.dump(message_data, file, indent=4)


def list_users(message_role):
    if message_role == "student":
        users = [chat_user for chat_user in student_data]
    elif message_role == "teacher":
        users = [chat_user for chat_user in teacher_data]
    elif message_role == "staff":
        users = [chat_user for chat_user in staff_data]
    elif message_role == "admin":
        users = [chat_user for chat_user in administrator_data]
    else:
        print("Invalid role.")
        return []

    # Display users with numbering and appropriate details
    print(f"\nList of {message_role}s:")
    for i, chat_user in enumerate(users, 1):
        if message_role == "student":
            print(f"{i}. {chat_user['name']} (Student ID: {chat_user['Student ID']})")
        elif message_role == "teacher":
            print(f"{i}. {chat_user['name']} (Department: {chat_user['department']})")
        elif message_role == "staff":
            print(f"{i}. {chat_user['name']} (Position: {chat_user['position']})")
        elif message_role == "admin":
            print(f"{i}. {chat_user['name']} (Department: {chat_user['department']})")
    return users


# Function to send a message
def send_message(sender, receiver, message):
    timestamp = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    message_entry = {
        "sender": sender,
        "receiver": receiver,
        "message": message,
        "timestamp": timestamp,
        "status": "Delivered"  # Set directly to "Delivered"
    }
    messages_data.append(message_entry)
    save_data("messages.json", messages_data)
    print(f"Message sent to {receiver} successfully!")


def mark_messages_as_read(logged_in_user, other_user_name):
    """Marks messages as 'Read' when the logged-in user views the chat."""
    try:
        with open("messages.json", "r") as f:
            messages_data = json.load(f)

        updated = False
        for msg in messages_data:
            # Match receiver and sender, and check status (case-sensitive)
            if (msg.get("receiver") == logged_in_user and
                    msg.get("sender") == other_user_name and
                    msg.get("status", "Delivered") == "Delivered"):
                msg["status"] = "Read"
                updated = True

        if updated:
            save_data("messages.json", messages_data)
            print("Messages marked as 'Read'.")

    except (FileNotFoundError, json.JSONDecodeError):
        print("Error: messages.json not found or corrupted.")  # Debug print


# Function to view chat history
def view_chat_history(logged_in_user, other_user_name):
    """Displays chat history and marks messages as 'Read'."""
    print(f"\n💬 Chat History between {logged_in_user} and {other_user_name}:")
    try:
        with open("messages.json", "r") as f:
            messages_data = json.load(f)

        chat_messages = [msg for msg in messages_data if
                         (msg["sender"] == logged_in_user and msg["receiver"] == other_user_name) or
                         (msg["sender"] == other_user_name and msg["receiver"] == logged_in_user)]

        if chat_messages:
            for msg in chat_messages:
                print(f"[{msg['timestamp']}] {msg['sender']}: {msg['message']} (Status: {msg['status']})")

            # Mark messages as read after displaying
            mark_messages_as_read(logged_in_user, other_user_name)
        else:
            print("📭 No messages found.")

    except (FileNotFoundError, json.JSONDecodeError):
        print("⚠️ No chat history available.")


def check_notifications(username):
    """Checks `messages.json` and only shows new 'Delivered' messages."""
    try:
        # Attempt to load messages from file
        with open("messages.json", "r") as f:
            messages_data = json.load(f)

        # Validate and normalize username
        if isinstance(username, str):
            user_name = username.strip()
        elif isinstance(username, dict):
            user_name = username.get("name", "").strip()
        else:
            print("⚠️ Invalid username format provided.")
            return

        # Filter new messages with safe key access
        new_messages = [
            msg for msg in messages_data
            if msg.get("receiver", "").strip() == user_name
            and msg.get("status", "Pending") == "Delivered"  # Default to "Pending" if status missing
        ]

        # Display results
        if new_messages:
            print("-" * 100)
            print(f"🔔 You have {len(new_messages)} new messages!")
            for msg in new_messages:
                # Use .get() with defaults to avoid KeyErrors
                timestamp = msg.get("timestamp", "N/A")
                sender = msg.get("sender", "Unknown")
                message = msg.get("message", "No content")
                print(f"[{timestamp}] {sender}: {message}")
                print("-" * 100)
        else:
            print("📭 No new messages found.")

    except FileNotFoundError:
        print("⚠️ Messages file 'messages.json' not found.")
    except json.JSONDecodeError:
        print("⚠️ Error reading messages file - invalid JSON format.")
    except Exception as e:
        print(f"⚠️ Unexpected error while checking notifications: {str(e)}")


# Combination of Save data, List users, Send message and View chat history
def chat_system(logged_in_user):
    while True:
        print("\nChat System")
        print("1. Send Message")
        print("2. View Chat History")
        print("3. Exit")
        try:
            chat_system_action = int(input("Choose an action: "))
        except ValueError:
            print("Invalid input. Please enter a number.")
            continue

        if chat_system_action == 1:  # Send Message
            print("Available roles to message:")
            print("1. Students")
            print("2. Teachers")
            print("3. Staff")
            print("4. Admin")
            try:
                role_choice = int(input("Choose a role to message: "))
                if role_choice == 1:
                    users = list_users("student")
                elif role_choice == 2:
                    users = list_users("teacher")
                elif role_choice == 3:
                    users = list_users("staff")
                elif role_choice == 4:
                    users = list_users("admin")
                else:
                    print("Invalid choice.")
                    continue

                if not users:
                    print("No users found in the selected role.")
                    continue

                try:
                    user_choice = int(input("Choose a user by number: "))
                    if 1 <= user_choice <= len(users):
                        receiver = users[user_choice - 1]
                        message = input("Type your message: ")
                        send_message(logged_in_user["name"], receiver["name"], message)
                    else:
                        print("Invalid user choice.")
                except ValueError:
                    print("Invalid input.")

            except ValueError:
                print("Invalid input.")

        elif chat_system_action == 2:  # View Chat History
            print("Available roles to view chat history:")
            print("1. Students")
            print("2. Teachers")
            print("3. Staff")
            print("4. Admin")
            try:
                role_choice = int(input("Choose a role: "))
                if role_choice == 1:
                    users = list_users("student")
                elif role_choice == 2:
                    users = list_users("teacher")
                elif role_choice == 3:
                    users = list_users("staff")
                elif role_choice == 4:
                    users = list_users("admin")
                else:
                    print("Invalid choice.")
                    continue

                if not users:
                    print("No users found in the selected role.")
                    continue

                try:
                    user_choice = int(input("Choose a user by number: "))
                    if 1 <= user_choice <= len(users):
                        other_user = users[user_choice - 1]
                        view_chat_history(logged_in_user["name"], other_user["name"])
                    else:
                        print("Invalid user choice.")
                except ValueError:
                    print("Invalid input.")

            except ValueError:
                print("Invalid input.")

        elif chat_system_action == 3:  # Exit
            print("Exiting chat system...")
            break
        else:
            print("Invalid action. Please choose between 1 and 3.")


# Function to log in for Admin
def admin_login(username, password):
    with open("administrator.json", "r") as admin_database:
        login_administrator_data = json.load(admin_database)
        for admin_data in login_administrator_data:

            username = admin_data["name"]
            password = admin_data["password"]

            if admin == username and admin_password == password:
                print(f"Hi {admin}")
                check_notifications(username)
                print("-" * 100)
                show_announcements_after_login("admin", username)
                break
            else:
                print("invalid username or password")
                quit()

        return username, password


# Post announcement
def post_announcement(sender_role, posted_by):
    title = input("Enter notification title: ")
    content = input("Enter notification content: ")
    target_role = input("Send to (students/teachers/staff/admins/everyone): ").strip().lower()
    timestamp = datetime.now().strftime("%Y-%m-%d %H:%M:%S")

    if target_role not in ['students', 'teachers', 'staff', 'admins', 'everyone']:
        print("❌ Invalid target role. Announcement not posted.")
        return

    new_note = {
        "title": title,
        "content": content,
        "posted_by": posted_by,
        "sender_role": sender_role,
        "target_role": target_role,
        "timestamp": timestamp,
        "viewed_by": []
    }

    announcements_data.append(new_note)
    announcement_save_data("announcement.json", announcements_data)
    print(f"✅ Notification sent by {sender_role} to {target_role}.")


def view_all_announcements(user_role):
    if not announcements_data:
        print("📭 No announcements found.")
        return

    print(f"📌 Announcements for {user_role}:")
    for note in announcements_data:
        if note.get("target_role") in ["everyone", user_role]:
            print(f"🔹 Title: {note.get('title', 'N/A')}")
            print(f"   Content: {note.get('content', 'N/A')}")
            print(f"   Posted By: {note.get('posted_by', 'N/A')} ({note.get('sender_role', 'N/A')})")
            print(f"   Date: {note.get('timestamp', 'N/A')}")
            print("-" * 100)


def search_announcements():
    keyword = input("Enter title to search in announcements: ").lower()
    found = False
    for note in announcements_data:
        if keyword in note.get("title", "").lower() or keyword in note.get("content", "").lower():
            print("-" * 40)
            print(f"🔹 Title: {note.get('title', 'N/A')}")
            print(f"   Content: {note.get('content', 'N/A')}")
            print(f"   Posted By: {note.get('posted_by', 'N/A')}")
            print(f"   Date: {note.get('timestamp', 'N/A')}")
            print(f"   Target Role: {note.get('target_role', 'N/A')}")
            print("-" * 40)
            found = True
    if not found:
        print("❌ No matching announcements found.")


def delete_announcement():
    title = input("Enter title to delete: ").strip().lower()
    original_len = len(announcements_data)
    announcements_data[:] = [note for note in announcements_data if note.get("title", "").lower() != title]

    if len(announcements_data) < original_len:
        save_data("announcement.json", announcements_data)
        print("✅ Announcement deleted successfully!")
    else:
        print("❌ Announcement not found.")


def announcement_save_data(filename, data):
    with open(filename, "w") as file:
        json.dump(data, file, indent=4)


def show_announcements_after_login(user_role, username):
    updated = False
    new_announcements = [
        note for note in announcements_data
        if note.get("target_role", "").lower() in ["everyone", user_role.lower()]
           and username not in note["viewed_by"]
    ]

    if len(new_announcements) > 0:
        for note in new_announcements:
            print(f"🔔 You have {len(new_announcements)} new announcements!")
            print(f"   Title: {note.get('title', 'N/A')}")
            print(f"   Content: {note.get('content', 'N/A')}")
            print(f"   Posted By: {note.get('posted_by', 'N/A')} ({note.get('sender_role', 'N/A')})")
            print(f"   Date: {note.get('timestamp', 'N/A')}")
            print("-" * 40)

            if username not in note["viewed_by"]:
                note["viewed_by"].append(username)
                updated = True

    if updated:
        try:
            save_data("announcement.json", announcements_data)
            print("✅ Announcement view status updated.")
        except (FileNotFoundError, json.JSONDecodeError):
            print("\n⚠️ No messages available or error reading the file.")


def announcements_menu(sender_role, posted_by, user_role):
    while True:
        print("\n--- Announcement System ---")
        print("1. View All Announcements")
        print("2. Search Announcement")
        print("3. Post Announcement")
        print("4. Delete Announcement")
        print("0. Exit")

        choice = input("Enter your choice: ")

        if choice == '1':
            view_all_announcements(user_role)
        elif choice == '2':
            search_announcements()
        elif choice == '3':
            post_announcement(sender_role, posted_by)
        elif choice == '4':
            delete_announcement()
        elif choice == '0':
            print("Exiting Announcement System...")
            break
        else:
            print("❌ Invalid choice!")


# Function for system administration
def system_administration(subject_number):
    while subject_number == 1:
        account_action = int(input("1.Check \n2.Update \n3.Delete \nChoose an action:"))
        if account_action == 1:
            system_administrator_teacher_name = input("Please input your username: ")
            system_administrator_teacher_password = str(input("Please input your password: "))

            with open("teacher.json", "r") as read_file:
                teacher_database = json.load(read_file)
                for teacher in teacher_database:

                    # valid teacher account
                    string_password = teacher["password"]

                    if system_administrator_teacher_name == teacher[
                        "name"] and system_administrator_teacher_password == string_password:
                        print(f"{teacher["name"]} user is valid")
                        break


        elif account_action == 2:
            try:
                with open("teacher.json", "r") as read_file:
                    update_teacher_data = json.load(read_file)
            except FileNotFoundError:
                update_teacher_data = []
            update_times = int(input("Number of teachers to update: "))
            default_update_times = 0

            while default_update_times < update_times:
                update_information = {
                    "name": input("Name: "),
                    "department": input("Department: "),
                    "email": input("Email: "),
                    "password": input("Password: ")
                }
                update_teacher_data.append(update_information)
                # store edited information in text file

                with open("teacher.json", "w") as update_file:
                    json.dump(update_teacher_data, update_file, indent=4)
                default_update_times += 1

                # print teacher updated information
                print(f"Updated successfully: {update_information}")

        elif account_action == 3:
            try:
                with open("teacher.json", "r") as read_file:
                    delete_teacher_data = json.load(read_file)
            except FileNotFoundError:
                delete_teacher_data = []
            with open("teacher.json", "r") as read_file:
                delete_data = json.load(read_file)

                teacher_data.append(read_file)
                print(delete_teacher_data)

                number = int(input("number delete"))
                for _ in delete_data:
                    # Print deleted successfully
                    print(f"Account deleted successfully\n{delete_data[number]}")
                    del delete_data[number]
                    break
                with open("teacher.json", "w") as delete_file:
                    json.dump(delete_data, delete_file, indent=4)

        break
    while subject_number == 2:
        account_action = int(input("1.Check student \n2.Add student \n3.Delete student \nChoose an action: "))
        if account_action == 1:
            check_student_name = input("Student full name: ").capitalize()
            check_student_password = str(input("Student password: "))

            with open("student.json", "r") as read_file:
                student_database = json.load(read_file)
                for student in student_database:

                    # valid teacher account
                    string_password = student["password"]

                    if check_student_name == student["name"] and check_student_password == string_password:
                        print(f"{student["name"]} user is valid")
                        break

        elif account_action == 2:
            add_student(student_data)

        elif account_action == 3:
            try:
                with open("student.json", "r") as read_file:
                    delete_student_data = json.load(read_file)
            except FileNotFoundError:
                delete_student_data = []
            with open("student.json", "r") as read_file:
                delete_data = json.load(read_file)

                delete_student_data.append(read_file)
                print(delete_student_data)

                number = int(input("number delete"))
                for _ in delete_data:
                    # Print deleted successfully
                    print(f"delete successfully\n{delete_data[number]}")
                    del delete_data[number]
                    break
                with open("student.json", "w") as delete_file:
                    json.dump(delete_data, delete_file, indent=4)

        break
    while subject_number == 3:
        account_action = int(input("Enter a number: 1.check 2.update 3.delete"))
        if account_action == 1:
            check_staff_name = input("Staff username")
            check_staff_password = str(input("Staff password"))

            with open("staff.json", "r") as read_file:
                staff_database = json.load(read_file)
                for staff in staff_database:

                    # valid teacher account
                    string_password = staff["password"]

                    if check_staff_name == staff["name"] and check_staff_password == string_password:
                        print(f"{staff["name"]} user is valid")
                        break
        elif account_action == 2:
            try:

                with open("staff.json", "r") as read_file:
                    update_staff_data = json.load(read_file)
            except FileNotFoundError:
                update_staff_data = []

            update_times = int(input("how many staff for updating"))
            default_update_times = 0

            while default_update_times < update_times:
                update_information = {
                    "name": input("name"),
                    "position": input("position"),
                    "email": input("email"),
                    "password": input("password")
                }

                update_staff_data.append(update_information)

                # store edited information in text file
                with open("staff.json", "w") as update_file:
                    json.dump(update_staff_data, update_file, indent=4)

                default_update_times += 1

                # print staff updated information
                print(f"Updated successfully: {update_information}")

        elif account_action == 3:
            try:
                with open("staff.json", "r") as read_file:
                    delete_staff_data = json.load(read_file)
            except FileNotFoundError:
                delete_staff_data = []

            with open("staff.json", "r") as read_file:
                delete_data = json.load(read_file)
                delete_staff_data.append(read_file)
                print(delete_staff_data)
                number = int(input("number delete"))

                for _ in delete_data:
                    # Print deleted successfully
                    print(f"delete successfully\n{delete_data[number]}")
                    del delete_data[number]
                    break

                with open("staff.json", "w") as delete_file:
                    json.dump(delete_data, delete_file, indent=4)

        break
    return subject_number


# Advanced action for course
def course_basic_feature():
    while True:
        course_management_basic_action = int(
            input("1. Create course \n2. Load course \n3. Manage course \n4. Exit \nChoose an action: "))
        if course_management_basic_action == 1:
            course_time = int(input("how many course create?"))
            default_course_time = 0

            while default_course_time < course_time:
                # input course detail for creating
                course_detail = {
                    "course": input("course"),
                    "instructor": input("instructor")
                }
                course_data.append(course_detail)

                # save in text file
                with open("course.json", "w") as create_file:
                    json.dump(course_data, create_file, indent=4)

                default_course_time += 1

                # print course created information
                print(f"Created successfully: {course_detail}")

        # checked created course
        elif course_management_basic_action == 2:
            with open("course.json", "r") as check_file:
                course_database = json.load(check_file)
                for course in course_database:
                    print(course)

        elif course_management_basic_action == 3:
            print("Welcome to course management advance action")
            course_advance_feature()

        elif course_management_basic_action == 4:
            print(f"{admin} sign out")
            break

    return


# Advanced action for course
def course_advance_feature():
    while True:
        course_management_advance_action = int(
            input("1. Update course \n2. Delete course \n3. Exit \nChoose an action: "))
        if course_management_advance_action == 1:
            with open("course.json", "r") as valid_file:
                read_course = json.load(valid_file)

                # course_data.append(read_course)
                print(read_course)

                update_course = str(input("enter course name"))

                for courses in read_course:
                    if update_course == courses["course"]:
                        print(courses)
                        # input course detail for updating
                        courses["course"] = input("course name")
                        # input assign instructor
                        courses["instructor"] = input("instructor name")
                        # save in text file
                        with open("course.json", "w") as update_file:
                            json.dump(read_course, update_file, indent=4)
                        print(f"Successfully updated schedule: {courses}")
                    break
            break

        # delete course
        elif course_management_advance_action == 2:

            with open("course.json", "r") as read_file:
                delete_data = json.load(read_file)

                course_data.append(read_file)
                print(course_data)

                # input course id for deleting
                number = int(input("number delete"))
                for _ in delete_data:
                    # print deleted successful
                    print(f"delete successfully\n{delete_data[number]}")
                    del delete_data[number]

                    break
                # save in text file
                with open("course.json", "w") as delete_file:
                    json.dump(delete_data, delete_file, indent=4)

            break
        elif course_management_advance_action == 3:
            print(f"{admin} sign out")
            break


# Functions to check schedule
def check_schedule():
    while True:
        class_schedule_basic_action = int(
            input("1. Check schedule \n2. Next action \n3. Exit \nChoose an action: "))
        if class_schedule_basic_action == 1:
            # print current schedule
            with open("timetable.json", "r") as check_file:
                check = json.load(check_file)
            print(check)
        elif class_schedule_basic_action == 3:
            break


# Function to generate a report
def report_generation(number):
    # select academic performance
    while number == 1:

        # while academic_action == 2:

        # select student or institution
        subject = int(input("1. student individual \n2. school institution"))

        # print academic performance

        if subject == 1:
            with open("results.json", "r") as academic_file:
                student_academic_data = json.load(academic_file)
                academic_data = student_academic_data
                print(academic_data)

                student_id = input("student id")
                string_result = "results"

                for record in academic_data[student_id][string_result]:
                    print(record)

            break

        elif subject == 2:
            with open("results.json", "r") as academic_file:
                student_academic_data = json.load(academic_file)

                print("School institution report:\n")
                for record in student_academic_data:
                    student_id = record
                    print(student_academic_data[student_id])
        break

    # break

    # select attendance report
    while number == 2:

        # select student or institution
        subject = int(input("1. student individual \n2. school institution"))
        # print attendance report
        if subject == 1:
            student_tp = input("Please type in your TP number: ").upper()
            view_attendance(student_tp)

        elif subject == 2:
            print("School institution report:\n")
            while True:
                exit_choice = input("Exit (y/n): ")
                if exit_choice == "y":
                    break
                student_tp = input("Please type in your TP number: ").upper()
                view_attendance(student_tp)

        break
    # select financial report
    while number == 3:
        financial_action = int(input("1. add \n2. generate"))
        while financial_action == 1:
            add_fees()
            break

        while financial_action == 2:
            # select student or institution
            subject = int(input("1. student individual \n2. school institution"))
            # print financial report
            if subject == 1:
                student_tp = input("Please type in your TP number: ").upper()
                print(f"Displaying fees for {student_tp}")
                view_fees(student_tp)

                break
            elif subject == 2:
                with open("fees.json", "r") as fee_file:
                    student_fee_data = json.load(fee_file)
                    fee_data = student_fee_data
                    print(fee_data)

                    string_fee = "fees"
                    course_place = int(input("course order start from 0"))
                    string_course = "course"

                    print("School institution report: \n")
                    for record in student_fee_data[string_fee][course_place][string_course]:
                        print(record)
                break
        break
    return number


#### Event Management Functions
# Function to add events
def add_event(event_id, name, date, location, description):
    if event_id in events_data:
        print("Event ID already exists. Choose a different ID.")
        return
    events_data[event_id] = {"name": name, "date": date, "location": location, "description": description}
    save_data("events.json", events_data)
    print("Event added successfully!")


# Function to search events
def edit_event():
    if not events_data:
        print("No events found.")
        return
    print("\nScheduled Events:")
    for event_id_key, event in events_data.items():
        print(
            f"ID: {event_id_key}, Name: {event['name']}, Date: {event['date']}, Location: {event['location']}, Description: {event['description']}")
    event_id_to_edit = input("Enter the event ID to edit: ")
    if event_id_to_edit in events_data:
        event_record = events_data[event_id_to_edit]
        print(f"Editing information for {event_record['name']}")
        event_record["name"] = input("Enter new name: ") or event_record["name"]
        event_record["date"] = input("Enter new date: ") or event_record["date"]
        event_record["location"] = input("Enter new location: ") or event_record["location"]
        event_record["description"] = input("Enter new description: ") or event_record["description"]
        with open("events.json", "w") as event_file:
            json.dump(events_data, event_file, indent=4)
        print("Information updated successfully!")
    else:
        print("Event ID not found!")


# Functon to delete events
def remove_event(event_id):
    if event_id in events_data:
        del events_data[event_id]
        save_data("events.json", events_data)
        print("Event removed successfully!")
    else:
        print("Event ID not found.")


# Function to view events
def view_events():
    if not events_data:
        print("No events found.")
        return
    print("\nScheduled Events:")
    for event_id_key, event in events_data.items():
        print(
            f"ID: {event_id_key}, Name: {event['name']}, Date: {event['date']}, Location: {event['location']}, Description: {event['description']}")


# Function for admin and stuff to manage events
def event_management():
    while True:
        print("\n--- Event Management System ---")
        print("1. View Events")
        print("2. Add Events")
        print("3. Edit Events")
        print("4. Remove Event")
        print("5. Return to Menu")

        admin_event_choice = input("Enter your choice: ")

        if admin_event_choice == "1":
            view_events()

        elif admin_event_choice == '2':
            event_id = generate_event_id()
            name = input("Enter Event Name: ")
            date = input("Enter Event Date (YYYY-MM-DD): ")
            location = input("Enter Event Location: ")
            description = input("Enter Event Description: ")
            add_event(event_id, name, date, location, description)

        elif admin_event_choice == "3":
            edit_event()

        elif admin_event_choice == '4':
            if not events_data:
                print("No events found.")
                return
            print("\nScheduled Events:")
            for event_id, event in events_data.items():
                print(
                    f"ID: {event_id}, Name: {event['name']}, Date: {event['date']}, Location: {event['location']}, Description: {event['description']}")
            event_id = input("Enter Event ID to remove: ")
            remove_event(event_id)

        elif admin_event_choice == '5':
            break

        else:
            print("Invalid choice! Please enter a valid option.")


# Menu Interface
while True:
    print("\nWelcome back!")
    print("1. Create an Account")
    print("2. Log in to your Account")
    print("3. Exit")
    try:
        login = int(input("Please Choose An Action: "))
    except ValueError:
        print("Invalid input. Please enter a number.")
        continue

    if login == 3:
        print("Thank You!")
        break

    elif login == 1:
        print("1. Student")
        print("2. Teacher")
        print("3. Staff")
        print("4. Administrator")
        try:
            role = int(input("Please choose a role: "))
        except ValueError:
            print("Invalid role input.")
            continue

        if role == 1:
            add_student(student_data)

        elif role == 2:
            add_teacher()

        elif role == 3:
            add_staff()

        elif role == 4:
            add_administrator()

        else:
            print("Invalid role! Please choose a valid role!")

    # Login Interface
    elif login == 2:
        print("1. Student")
        print("2. Teacher")
        print("3. Staff")
        print("4. Admin")
        try:
            login_role = int(input("Please enter your role: "))
        except ValueError:
            print("Invalid role input.")
            continue

        # Student Interface
        if login_role == 1:
            # Reload student data before each login attempt
            student_data = load_student_data()
            if not student_data:
                print("No student data available. Please contact support.")
                continue

            student_tp = input("Please type in your TP number: ").upper()
            student_password = input("Please type in your password: ")

            user_found = False
            account_suspended = False
            for user in student_data:
                if user["Student ID"] == student_tp:
                    user_found = True
                    if user["password"] == student_password:
                        if user["status"] == "active":
                            print("Access granted! Welcome!")
                            print("-" * 100)
                            print("🔔Notification Panel:")
                            check_notifications(user)
                            print("-" * 100)
                            username = user["name"]
                            show_announcements_after_login("student", username)
                            while True:
                                print("\nPlease choose an action:")
                                print("1. Check Timetable")
                                print("2. Manage Assignments")
                                print("3. Check Fees Status")
                                print("4. Edit Information")
                                print("5. Exam Results")
                                print("6. Chat System")
                                print("7. Feedback Submission")
                                print("8. Manage Attendance")
                                print("9. View All Announcement")
                                print("10. Log out")
                                try:
                                    action = int(input("Choose an action: "))
                                except ValueError:
                                    print("Invalid input. Please enter a number.")
                                    continue

                                if action == 1:
                                    print("Loading Timetable...")
                                    timetable_choice = int(input("1. Class Timetable \n2. Exam Timetable \n3. Exit \nChoose An Action: "))
                                    if timetable_choice == 1:
                                        timetable_student_course = user.get("course")
                                        if timetable_student_course:
                                            load_timetable_by_course(timetable_student_course)
                                        else:
                                            print("Error: No course found for this student.")


                                    elif timetable_choice == 2:
                                        timetable_student_course = user.get("course")
                                        if timetable_student_course:
                                            load_exam_schedule_by_course(timetable_student_course)
                                        else:
                                            print("Error: No course found for this student.")

                                    elif timetable_choice == 3:
                                        continue

                                    else:
                                        print("Invalid Choice!")


                                elif action == 2:
                                    course_name = user["course"]
                                    print("Loading your Course Details...")
                                    print("1. View Assignments")
                                    print("2. Submit Assignments")
                                    student_assignment_action = int(input("Choose an action: "))
                                    if student_assignment_action == 1:
                                        load_course_assignments(course_name)
                                    elif student_assignment_action == 2:
                                        submit_assignment(student_tp)
                                    else:
                                        print("Invalid option!")

                                elif action == 3:
                                    print(f"Displaying fees for {user["name"]}")
                                    view_fees(student_tp)

                                elif action == 4:
                                    edit_information(student_tp)

                                elif action == 5:
                                    view_results(student_tp)

                                elif action == 6:
                                    print("Loading Chat System...")
                                    chat_system(user)

                                elif action == 7:
                                    print("Feedback Submission...")
                                    add_feedback()

                                elif action == 8:
                                    manage_attendance_action = int(input(
                                        "Managing Attendance... \n1. Mark Attendance \n2. View Attendance \n3. Exit \nChoose an action: "))
                                    if manage_attendance_action == 1:
                                        mark_attendance(student_tp)
                                    elif manage_attendance_action == 2:
                                        view_attendance(student_tp)
                                    else:
                                        continue

                                elif action == 9:
                                    view_all_announcements(user_role="student")

                                elif action == 10:
                                    print("Logging out...")
                                    break
                                else:
                                    print("Invalid action. Please choose between 1 and 10.")
                            break
                        else:
                            account_suspended = True
                    break

            if not user_found:
                print("Access denied! Invalid Student ID.")
            elif account_suspended:
                print("Access denied! Your account is suspended or withdrawn.")

        # Teacher Interface
        elif login_role == 2:
            teacher_name = input("Please type in your name: ").capitalize()
            teacher_password = input("Please type in your password: ")

            # Open and read the JSON file
            with open("teacher.json", "r") as teacher_file:
                data = json.load(teacher_file)  # Parse JSON content

            # Check if the user exists and password matches
            user_found = False
            for user in data:  # Iterate through the list of users directly
                if user["name"] == teacher_name and user["password"] == teacher_password:
                    user_found = True
                    print("Access granted! Welcome,", teacher_name)
                    print("-" * 100)
                    print("🔔Notification Panel:")
                    check_notifications(user)
                    print("-" * 100)
                    show_announcements_after_login("teacher", teacher_name)

                    # After successful login, present additional options
                    while True:
                        print("\nPlease choose an action:")
                        print("1. View Timetable")
                        print("2. Manage Assignments")
                        print("3. Manage Results")
                        print("4. Manage Course")
                        print("5. View Feedback")
                        print("6. Manage Attendance")
                        print("0. Log out")
                        try:
                            action = int(input("Choose an action: "))
                        except ValueError:
                            print("Invalid input. Please enter a number.")
                            continue

                        if action == 1:
                            try:
                                timetable_action = int(
                                    input("1. Class Timetable \n2. Exam Timetable \n3. Exit \nChoose An Action: "))
                                if timetable_action == 1:
                                    view_all_timetables(timetables)

                                elif timetable_action == 2:
                                    view_all_exam_schedule(exam_data)

                                elif timetable_action == 3:
                                    break

                                else:
                                    print("Invalid Input!")

                            except ValueError:
                                print("Invalid input! Please enter a number (1, 2, or 3).")

                        elif action == 2:
                            print("Managing Assignments...")
                            print("1. View assignments")
                            print("2. Add assignments")
                            print("3. Edit assignments")
                            print("4. Remove assignments")
                            print("5. View Submissions")
                            print("7. View All Announcement")
                            print("0. Log out")
                            assignment_action = int(input("Choose an action: "))
                            if assignment_action == 1:
                                view_assignments_by_course()

                            elif assignment_action == 2:
                                add_assignment()

                            elif assignment_action == 3:
                                edit_assignment()

                            elif assignment_action == 4:
                                remove_assignment()

                            elif assignment_action == 5:
                                view_submissions()

                            elif assignment_action == 0:
                                continue

                            else:
                                print("Invalid choice! Please choose between 1-7")

                        elif action == 3:
                            print("Managing Results...")
                            print("1. Add Results")
                            print("2. Edit Results")
                            print("3. View Results")
                            print("4. Exit")
                            manage_results_action = int(input("Choose an action: "))
                            if manage_results_action == 1:
                                add_results()

                            elif manage_results_action == 2:
                                edit_results()

                            elif manage_results_action == 3:
                                view_all_results()

                            elif manage_results_action == 4:
                                continue

                        elif action == 4:
                            manage_course()

                        elif action == 5:
                            print("Viewing Feedback...")
                            view_feedback_teacher()

                        elif action == 6:
                            teacher_manage_attendance = int(input(
                                "Managing Attendance... \n1. Add Attendance \n2. View Student Attendance \n3. Exit \nChoose an action: "))
                            if teacher_manage_attendance == 1:
                                add_attendance()

                            elif teacher_manage_attendance == 2:
                                view_student_attendance_teacher()

                            else:
                                continue
                        elif action == 7:
                            view_all_announcements(user_role="teacher")
                        elif action == 0:
                            print("Logging out...")
                            break  # Exit to the main menu

                        else:
                            print("Invalid action. Please choose between 1 and 4.")
                    break  # Exit login loop

            if not user_found:
                print("Access denied! Invalid username or password.")

        # Staff Interface
        elif login_role == 3:
            staff_name = input("Please type in your name: ").capitalize()
            staff_password = input("Please type in your password: ")

            # Open and read the JSON file
            with open("staff.json", "r") as staff_file:
                data = json.load(staff_file)  # Parse JSON content

            # Check if the user exists and password matches
            user_found = False
            for user in data:  # Iterate through the list of users directly
                if user["name"] == staff_name and user["password"] == staff_password:
                    user_found = True
                    print("Access granted! Welcome,", staff_name, '😊')
                    print("-" * 100)
                    print("🔔Notification Panel:")
                    check_notifications(user)
                    print("-" * 100)
                    show_announcements_after_login("staff", staff_name)

                    # After successful login, present additional options
                    while True:
                        print("\nPlease choose an action:")
                        print("1. Student Record Management")
                        print("2. Timetable Management")
                        print("3. Announcement Management")
                        print("4. Fee Management System ")
                        print("5. Chat System")
                        print("6. Event Management")
                        print("7. View Feedback from Teacher ")
                        print("0. Log out")
                        try:
                            action = int(input("Choose an action: "))
                        except ValueError:
                            print("Invalid input. Please enter a number.")
                            continue

                        if action == 1:
                            print("Running Student Record Management...")
                            student_record()

                        elif action == 2:
                            try:
                                timetable_action = int(
                                    input("1. Class Timetable \n2. Exam Timetable \n3. Exit \nChoose An Action: "))
                                if timetable_action == 1:
                                    manage_timetable()

                                elif timetable_action == 2:
                                    manage_exam_schedule()

                                elif timetable_action == 3:
                                    break

                                else:
                                    print("Invalid Input!")

                            except ValueError:
                                print("Invalid input! Please enter a number (1, 2, or 3).")


                        elif action == 3:
                            print("Displaying Announcement system...")
                            announcements_menu("staff", staff_name, user_role="staff")
                        elif action == 4:
                            print("Displaying Fee System...")
                            fee_system()

                        elif action == 5:
                            print("Displaying Chat System...")
                            chat_system(user)  # Pass the entire user dictionary

                        elif action == 6:
                            print("Displaying Event Management System...")
                            event_management()

                        elif action == 7:
                            print("Displaying Feedback System...")
                            view_feedback_staff()

                        elif action == 0:
                            print("Logging out...")
                            break  # Exit to the main menu
                        else:
                            print("Invalid action. Please choose between 1 and 4.")
                    break  # Exit login loop

            if not user_found:
                print("Access denied! Invalid username or password.")

        # Admin Interface
        elif login_role == 4:
            admin = input("Please type in your name: ").capitalize()
            admin_password = input("Please type in your password: ")

            admin_login(admin, admin_password)

            # menu action for admin
            action_list = ["System Administration",
                           "Student Management",
                           "Course Management",
                           "Class Schedule",
                           "Report Generation",
                           "Event Management System",
                           "Announcement System",
                           "Chat System",
                           "Log Out"
                           ]
            while True:
                print(
                    f"1. {action_list[0]}\n2. {action_list[1]}\n3. {action_list[2]}\n4. {action_list[3]}\n5. {action_list[4]}\n6. {action_list[5]} \n7. {action_list[6]}\n8. {action_list[7]} \n0. {action_list[8]}")
                try:
                    admin_action = int(input("Choose an action: "))
                except ValueError:
                    print("Invalid input. Please enter a number.")
                    continue

                if admin_action == 1:
                    print(f"Welcome to {action_list[0]}")
                    subject = int(input("1.Teacher \n2.Student \n3.Staff \nChoose a role: "))

                    system_administration(subject)

                # Student management
                elif admin_action == 2:
                    print(f"Welcome to {action_list[1]}")

                    student_record()

                # Course management
                elif admin_action == 3:
                    print(f"Welcome to {action_list[2]}")
                    course_basic_feature()

                # Class schedule
                elif admin_action == 4:
                    print(f"Welcome to {action_list[3]}")
                    manage_timetable()

                # Report generation
                elif admin_action == 5:
                    print(f"Welcome to {action_list[4]}")
                    report_generation_action = int(
                        input("1. Academic performance \n2. Attendance report \n3. Financial report \nChoose an action:"))
                    report_generation(report_generation_action)

                elif admin_action == 6:
                    print(f"Welcome to {action_list[5]}")
                    event_management()

                elif admin_action == 7:
                    print(f"Welcome to {action_list[6]}")
                    announcements_menu(sender_role="admin", posted_by="admin", user_role="admin")

                elif admin_action == 8:
                    print(f"Welcome to {action_list[7]}")
                    chat_system(logged_in_user={"name": "admin"})

                elif admin_action == 0:
                    print(f"You have {action_list[8]}. Thanks for using our system!")
                    break

# 1. Loading Configuration (load_config)
# The script first loads a configuration file (config.json), which contains:
# A list of days (Monday to Friday, for example).
# A list of subjects (like Math, Science, History, etc.).
# A list of timeslots (e.g., 09:00 AM, 10:30 AM, etc.).
# If the config file is missing, it shows an error.
# 2. Generating a Random Timetable (generate_random_timetable)
# It picks random subjects for each day (between 5 and 7 subjects).
# It assigns available time slots to those subjects.
# It ensures the timetable starts from today's date.
# 3. Saving or Loading an Existing Timetable (load_or_save_timetable)
# If a timetable.json file exists, it loads it.
# If not, it generates new timetables for three different courses (Course1, Course2, Course3).
# The generated timetable is saved in a file (timetable.json) so it doesn't reset every time.
# 4. Editing the Timetable (edit_timetable)
# The user can choose a course (Course1, Course2, or Course3).
# They can pick a day (e.g., Monday) and see the current schedule.
# They can then:
# Add a new subject if there are empty time slots.
# Remove a subject if they don’t want it anymore.
# Change a subject or it's time.
# 5. Generating a New Timetable (generate_new_timetables)
# The user can completely regenerate timetables for a specific course if needed.
# 6. Displaying Timetables (display_timetables)
# It prints the latest timetables in a readable format.
# 7. Main Menu (choose_action)
# The user gets 3 options:
# Edit a timetable (modify subjects, times).
# Generate a new timetable (replace old one for a course).
# Exit (quit the program).
# Final Summary:
# The script loads an existing timetable or creates a new one.
# The user can view, edit, or regenerate schedules.
# Any changes made are saved so they aren’t lost.
# The program keeps running until the user decides to exit.
