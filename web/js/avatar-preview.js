const fileChooserElement = document.querySelector('#profile-avatar');
const previewElement = document.querySelector('.avatar-preview');

const FILE_TYPES = ['gif', 'jpg', 'jpeg', 'png'];

fileChooserElement.addEventListener('change', () => {
  const file = fileChooserElement.files[0];
  const fileName = file.name.toLowerCase();
  const matches = FILE_TYPES.some((it) => fileName.endsWith(it));
  if (matches) {
    previewElement.src = URL.createObjectURL(file);
  }
});
